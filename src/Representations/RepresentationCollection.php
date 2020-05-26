<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

use ArrayObject;
use function array_filter;
use function array_key_exists;
use function call_user_func;
use function is_callable;
use function iterator_to_array;

class RepresentationCollection implements RepresentationCollectionInterface
{
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function filter(callable $filter): RepresentationCollectionInterface
    {
        return new RepresentationCollection(array_filter(iterator_to_array($this), $filter));
    }

    public function map(callable $callback): RepresentationCollectionInterface
    {
        return new RepresentationCollection(array_map($callback, iterator_to_array($this)));
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function first(?callable $callback = null): ?RepresentationInterface
    {
        if (!is_callable($callback)) {
            return count($this->items) > 0 ? reset($this->items) : null;
        }
        foreach ($this->getIterator() as $item) {
            if (call_user_func($callback, $item)) {
                return $item;
            }
        }
        return null;
    }

    public function getIterator(): iterable
    {
        return new ArrayObject($this->items);
    }
}
