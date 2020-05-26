<?php

namespace App\Keycloak\Admin\Guzzle;

use App\Keycloak\Admin\Token\TokenManager;
use Psr\Http\Message\RequestInterface;
use function array_key_exists;
use function parse_url;
use function preg_match;
use const PHP_URL_PATH;

class TokenMiddleware
{
    /**
     * @var TokenManager
     */
    private $tokenManager;

    private $defaultRealm;

    public function __construct(TokenManager $tokenManager, string $defaultRealm)
    {
        $this->tokenManager = $tokenManager;
        $this->defaultRealm = $defaultRealm;
    }

    /**
     * Called when the middleware is handled by the client.
     *
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler)
    {
        $manager = $this->tokenManager;
        return function (
            RequestInterface $request,
            array $options
        ) use (
            $handler,
            $manager
        ) {
            if (false !== ($realm = $this->getRequestedRealm($request, $options))) {
                $token = $manager->getToken($realm);
                $request = $request->withHeader('Authorization', sprintf('%s %s', $token->getType(), (string)$token));
            }
            return $handler($request, $options);
        };
    }

    /**
     * Determines the requested realm by inspecting the options and request uri
     *
     * @param RequestInterface $request
     * @param array $options
     * @param string $default
     * @return bool|mixed
     */
    private function getRequestedRealm(RequestInterface $request, array $options)
    {
        $realm = array_key_exists('realm', $options) ? $options['realm'] : $this->defaultRealm;

        if (!$realm) {
            $path = parse_url((string)$request->getUri(), PHP_URL_PATH);
            if (preg_match('(^/auth/admin/realms/(?P<realm>[^/]+)/)i', $path, $matches)) {
                $realm = $matches['realm'];
            }
        }
        return $realm;
    }
}
