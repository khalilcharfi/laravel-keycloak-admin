<?php

namespace Scito\Laravel\Keycloak\Admin;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Scito\Keycloak\Admin\ClientBuilder;

/**
 * Class ClientManager
 * @package Keycloak\Admin
 */
class ClientManager
{
    private $connections = [];

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param null|string $name
     * @return mixed
     */
    public function connection(?string $name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    protected function configuration($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->app['config']['keycloak-admin.connections'];

        if (is_null($config = Arr::get($connections, $name))) {
            throw new InvalidArgumentException("Keycloak API [{$name}] not configured.");
        }

        return $config;
    }

    protected function makeConnection($name)
    {
        $config = $this->configuration($name);

        if (null === ($username = Arr::get($config, 'username'))) {
            throw new \RuntimeException("Username not set");
        }
        if (null === ($password = Arr::get($config, 'password'))) {
            throw new \RuntimeException("Password not set");
        }
        if (null === ($realm = Arr::get($config, 'realm'))) {
            throw new \RuntimeException("Realm not set");
        }

        return (new ClientBuilder())->withServerUrl($config['url'] ?? null)
            ->withUsername($username)
            ->withPassword($password)
            ->withRealm($realm)
            ->withClientId($config['client-id'] ?? null)
            ->withClientSecret($config['client-secret'] ?? null)
            ->build();
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['keycloak-admin.default'];
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
