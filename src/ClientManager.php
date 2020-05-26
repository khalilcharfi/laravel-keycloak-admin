<?php

namespace Kcharfi\Laravel\Keycloak\Admin;

use App\Keycloak\Admin\ClientBuilder;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use RuntimeException;

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

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
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

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['keycloak-admin.default'];
    }

    protected function makeConnection($name)
    {
        $config = $this->configuration($name);

        if (null === ($username = Arr::get($config, 'username'))) {
            throw new RuntimeException("Username not set");
        }
        if (null === ($password = Arr::get($config, 'password'))) {
            throw new RuntimeException("Password not set");
        }
        if (null === ($realm = Arr::get($config, 'realm'))) {
            throw new RuntimeException("Realm not set");
        }

        return (new ClientBuilder())->withServerUrl($config['url'] ?? null)
            ->withUsername($username)
            ->withPassword($password)
            ->withRealm($realm)
            ->withClientId($config['client-id'] ?? null)
            ->withClientSecret($config['client-secret'] ?? null)
            ->build();
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
}
