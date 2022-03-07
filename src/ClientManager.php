<?php

namespace Kcharfi\Laravel\Keycloak\Admin;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Kcharfi\Laravel\Keycloak\Admin\Enums\GrantType;
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
        $grantType = Arr::get($config, 'grant-type') ?: GrantType::PASSWORD;
        $clientId = Arr::get($config, 'client-id');
        $clientSecret = Arr::get($config, 'client-secret');

        switch ($grantType) {
            case GrantType::PASSWORD:
                if (null === ($username = Arr::get($config, 'username'))) {
                    throw new RuntimeException("Username not set");
                }
                if (null === ($password = Arr::get($config, 'password'))) {
                    throw new RuntimeException("Password not set");
                }
                break;

            case GrantType::CLIENT_CREDENTIALS:
                if (null === $clientId) {
                    throw new RuntimeException("client-id not set");
                }
                if (null === $clientSecret) {
                    throw new RuntimeException("client-secret not set");
                }
                break;
        }

        if (null === ($realm = Arr::get($config, 'realm'))) {
            throw new RuntimeException("Realm not set");
        }

        return (new ClientBuilder())
            ->withConfig(new Config(
                $realm ?? 'master',
                $config['url'] ?? null,
                $clientId ?? null,
                $clientSecret ?? null,
                $grantType ?? GrantType::PASSWORD,
                $username ?? "",
                $password ?? ""
            ))
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
