<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Tests\Traits;

use Kcharfi\Laravel\Keycloak\Admin\Client;
use Kcharfi\Laravel\Keycloak\Admin\ClientBuilder;

trait WithTestClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @before
     */
    public function setupClientClass()
    {
        $this->client = $this->makeClient();
    }

    protected function makeClient()
    {
        return (new ClientBuilder())
            ->withRealm($_SERVER['REALM'])
            ->withServerUrl($_SERVER['SERVER_URL'])
            ->withClientId($_SERVER['CLIENT_ID'])
            ->withUsername($_SERVER['USERNAME'])
            ->withPassword($_SERVER['PASSWORD'])
            ->build();
    }

    /**
     * @after
     */
    public function teardownClientClass()
    {
        $this->client = null;
    }
}
