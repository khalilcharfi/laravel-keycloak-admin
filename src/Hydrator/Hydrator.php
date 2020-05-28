<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Hydrator;

use Kcharfi\Laravel\Keycloak\Admin\Exceptions\AnnotationException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;
use RuntimeException;
use SplFileObject;
use Traversable;
use function is_array;
use function is_object;
use function preg_replace;
use function substr;

class Hydrator implements HydratorInterface
{
    private $docReader;

    private $primitiveTypes = [
        'bool',
        'boolean',
        'string',
        'int',
        'integer',
        'float',
        'double',
        'array',
        'object',
        'callable',
        'resource',
        'mixed'
    ];

    public function __construct()
    {
    }

    public function extract($object): array
    {
        $attributes = array();

        $class = get_class($object);

        $methods = get_class_methods($class);

        foreach ($methods as $method) {
            if (!preg_match(' /^(get|is)(.*?)$/i', $method, $results)) {
                continue;
            }

            $k = $results[2];

            $k = strtolower(substr($k, 0, 1)) . substr($k, 1);

            $value = $object->$method();

            if (is_array($value)) {
                $attributes[$k] = [];
                foreach ($value as $_k => $v) {
                    if (is_object($v)) {
                        $attributes[$k][$_k] = $this->extract($v);
                    } else {
                        $attributes[$k][$_k] = $v;
                    }
                }
            } else {
                $attributes[$k] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Attempts to resolve the FQN of the provided $type based on the $class and $member context, specifically
     * searching through the traits that are used by the provided $class.
     *
     * @param string $type
     * @param ReflectionClass $class
     * @param Reflector $member
     *
     * @return string|null Fully qualified name of the type, or null if it could not be resolved
     */
    private function tryResolveFqnInTraits($type, ReflectionClass $class, Reflector $member)
    {
        /** @var ReflectionClass[] $traits */
        $traits = array();

        // Get traits for the class and its parents
        while ($class) {
            $traits = array_merge($traits, $class->getTraits());
            $class = $class->getParentClass();
        }

        foreach ($traits as $trait) {
            // Eliminate traits that don't have the property/method/parameter
            if ($member instanceof ReflectionProperty && !$trait->hasProperty($member->name)) {
                continue;
            } elseif ($member instanceof ReflectionMethod && !$trait->hasMethod($member->name)) {
                continue;
            } elseif ($member instanceof ReflectionParameter &&
                !$trait->hasMethod($member->getDeclaringFunction()->name)) {
                continue;
            }

            // Run the resolver again with the ReflectionClass instance for the trait
            $resolvedType = $this->tryResolveFqn($type, $trait, $member);

            if ($resolvedType) {
                return $resolvedType;
            }
        }
        return null;
    }

    /**
     * Attempts to resolve the FQN of the provided $type based on the $class and $member context.
     *
     * @param string $type
     * @param ReflectionClass $class
     * @param Reflector $member
     *
     * @return string|null Fully qualified name of the type, or null if it could not be resolved
     */
    private function tryResolveFqn($type, ReflectionClass $class, Reflector $member)
    {
        $alias = ($pos = strpos($type, '\\')) === false ? $type : substr($type, 0, $pos);
        $loweredAlias = strtolower($alias);

        // Retrieve "use" statements
        $uses = $this->parseUseStatements($class);

        if (isset($uses[$loweredAlias])) {
            // Imported classes
            if ($pos !== false) {
                return $uses[$loweredAlias] . substr($type, $pos);
            } else {
                return $uses[$loweredAlias];
            }
        } elseif ($this->classExists($class->getNamespaceName() . '\\' . $type)) {
            return $class->getNamespaceName() . '\\' . $type;
        } elseif (isset($uses['__NAMESPACE__']) && $this->classExists($uses['__NAMESPACE__'] . '\\' . $type)) {
            // Class namespace
            return $uses['__NAMESPACE__'] . '\\' . $type;
        } elseif ($this->classExists($type)) {
            // No namespace
            return $type;
        }

        // If all fail, try resolving through related traits
        return $this->tryResolveFqnInTraits($type, $class, $member);
    }

    /**
     * @return array A list with use statements in the form (Alias => FQN).
     */
    public function parseUseStatements(ReflectionClass $class)
    {
        if (false === $filename = $class->getFilename()) {
            return array();
        }

        $content = $this->getFileContent($filename, $class->getStartLine());

        if (null === $content) {
            return array();
        }

        $namespace = preg_quote($class->getNamespaceName());
        $content = preg_replace('/^.*?(\bnamespace\s+' . $namespace . '\s*[;{].*)$/s', '\\1', $content);
        $tokenizer = new \PhpDocReader\PhpParser\TokenParser('<?php ' . $content);

        $statements = $tokenizer->parseUseStatements($class->getNamespaceName());

        return $statements;
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string $filename The name of the file to load.
     * @param integer $lineNumber The number of lines to read from file.
     *
     * @return string The content of the file.
     */
    private function getFileContent($filename, $lineNumber)
    {
        if (!is_file($filename)) {
            return null;
        }

        $content = '';
        $lineCnt = 0;
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            if ($lineCnt++ == $lineNumber) {
                break;
            }

            $content .= $file->fgets();
        }

        return $content;
    }

    /**
     * @param string $class
     * @return bool
     */
    private function classExists($class)
    {
        return class_exists($class) || interface_exists($class);
    }

    private function resolveParameters(ReflectionMethod $method, array $data)
    {
        $args = [];

        foreach ($method->getParameters() as $parameter) {
            [$type, $isArray] = $this->getParameterType($parameter);
            $args[] = $this->resolveParameter($parameter, $data, $type, $isArray);
        }

        return $args;
    }

    /**
     * Parse the docblock of the property to get the class of the param annotation.
     *
     * @param ReflectionParameter $parameter
     *
     * @return array Type of the property (content of var annotation)
     * @throws AnnotationException
     */
    public function getParameterType(ReflectionParameter $parameter)
    {
        // Use reflection
        $parameterClass = $parameter->getClass();
        if ($parameterClass !== null) {
            return [$parameterClass->name, false];
        }

        $parameterName = $parameter->name;
        // Get the content of the @param annotation
        $method = $parameter->getDeclaringFunction();
        $comment = $method->getDocComment();
        if (!preg_match('/@param\s+([^\s]+)\s+\$' . $parameterName . '/', $comment, $matches)) {
            return [null, false];
        }

        list(, $type) = $matches;

        $isArray = substr($type, -2) == '[]';

        if ($isArray) {
            $type = substr($type, 0, -2);
        }

        // Ignore primitive types
        if (in_array($type, $this->primitiveTypes)) {
            return [$type, $isArray];
        }

        $class = $parameter->getDeclaringClass();

        // If the class name is not fully qualified (i.e. doesn't start with a \)
        if ($type[0] !== '\\') {
            // Try to resolve the FQN using the class context
            $resolvedType = $this->tryResolveFqn($type, $class, $parameter);

            if (!$resolvedType) {
                throw new AnnotationException(sprintf(
                    'The @param annotation for parameter "%s" of %s::%s contains a non existent class "%s". ' .
                    'Did you maybe forget to add a "use" statement for this annotation?',
                    $parameterName,
                    $class->name,
                    $method->name,
                    $type
                ));
            }

            $type = $resolvedType;
        }

        if (!$this->classExists($type)) {
            throw new AnnotationException(sprintf(
                'The @param annotation for parameter "%s" of %s::%s contains a non existent class "%s"',
                $parameterName,
                $class->name,
                $method->name,
                $type
            ));
        }

        // Remove the leading \ (FQN shouldn't contain it)
        $type = ltrim($type, '\\');

        return [$type, $isArray];
    }

    private function resolveParameter(ReflectionParameter $parameter, array $data, $class, $isArray)
    {
        $name = $parameter->getName();
        if (null !== ($type = $parameter->getType())) {
            $type = $parameter->getType()->getName();
        }

        $value = $data[$name] ?? null;

        if (null === $value && !$parameter->isOptional()) {
            throw new RuntimeException("Parameter $name is required");
        }

        if ($isArray && $class) {
            return $this->createArrayOfType($class, $value);
        }

        if ($parameter->getType()->isBuiltin()) {
            return $this->convertToBuiltinType($value, $type);
        }

        return null;
    }

    private function createArrayOfType($class, $value)
    {
        if (null === $value) {
            return [];
        }

        if (is_array($value) || $value instanceof Traversable) {
            $result = [];
            foreach ($value as $k => $v) {
                if (is_array($v)) {
                    $result[$k] = $this->hydrate($v, $class);
                } else {
                    $result[$k] = $v;
                }
            }
            return $result;
        }
    }

    public function hydrate(array $data, $class)
    {
        $ref = new ReflectionClass($class);

        $constructor = $ref->getConstructor();

        $args = $this->resolveParameters($constructor, $data);

        return $ref->newInstanceArgs($args);
    }

    private function convertToBuiltinType($value, $type)
    {
        switch ($type) {
            case 'string':
                return (string)$value;
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        return $value;
    }
}
