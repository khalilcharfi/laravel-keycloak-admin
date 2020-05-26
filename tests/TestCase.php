<?php


use Kcharfi\Laravel\Keycloak\Admin\KeycloakServiceProvider;


abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function assertValidKeycloakId($id)
    {
        $this->assertTrue(
            filter_var(preg_match('/^([a-z0-9\-]){36}$/', $id), FILTER_VALIDATE_BOOLEAN)
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [KeycloakServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('keycloak-admin.default', 'master');
        $app['config']->set('keycloak-admin.connections', [
            'master' => [
                'realm' => 'master',
                'url' => 'http://keycloak:8080',
                'username' => 'admin',
                'password' => 'secret',
                'client-id' => 'admin-cli',
                'logging' => [
                    'channel' => 'single'
                ]
            ]
        ]);
        $app['config']->set('logging.channels', [
            'default' => 'single',
            'single' => [
                'driver' => 'single',
                'path' => storage_path('logs/laravel.log'),
                'level' => 'debug',
            ],
        ]);
    }
}
