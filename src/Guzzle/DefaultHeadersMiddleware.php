<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Guzzle;

use Psr\Http\Message\RequestInterface;

class DefaultHeadersMiddleware
{
    /**
     * Called when the middleware is handled by the client.
     *
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $request = $request->withHeader('Accept', 'application/json');
            $request = $request->withHeader('Content-Type', 'application/json');
            return $handler($request, $options);
        };
    }
}
