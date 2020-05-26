<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Kcharfi\Laravel\Keycloak\Admin\Client;
use Kcharfi\Laravel\Keycloak\Admin\Facades\KeycloakAdmin;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RolesResource;
use Kcharfi\Laravel\Keycloak\Admin\Resources\UsersResource;
use RuntimeException;

class KeycloakFacadeTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function the_keycloak_facade_generates_a_client()
    {
        $client = KeycloakAdmin::connection('master');
        $this->assertInstanceOf(Client::class, $client);
    }


    /**
     * @test
     */
    public function the_default_realm_is_used_for_the_users_resource_when_no_realm_is_specified()
    {
        $resource = KeycloakAdmin::users();
        $this->assertInstanceOf(UsersResource::class, $resource);
    }

    /**
     * @test
     */
    public function the_default_realm_is_used_for_the_roles_resource_when_no_realm_is_specified()
    {
        $resource = KeycloakAdmin::roles();
        $this->assertInstanceOf(RolesResource::class, $resource);
    }

    /**
     * @test
     */
    public function a_exception_is_thrown_when_the_user_resource_is_accessed_directly_without_setting_a_default_realm()
    {
        $this->app['config']->set('keycloak-admin.connections.master', [
            'url' => 'http://keycloak:8080'
        ]);

        $this->expectException(RuntimeException::class);

        KeycloakAdmin::users();
    }

    /**
     * @test
     */
    public function a_exception_is_thrown_when_the_username_is_not_set_in_the_configuration()
    {
        $this->app['config']->set('keycloak-admin.connections.master', [
            'url' => 'http://keycloak:8080',
            'password' => $this->faker->password,
            'realm' => $this->faker->slug
        ]);

        $this->expectException(RuntimeException::class);

        KeycloakAdmin::roles();
    }

    /**
     * @test
     */
    public function a_exception_is_thrown_when_the_password_is_not_set_in_the_configuration()
    {
        $this->app['config']->set('keycloak-admin.connections.master', [
            'url' => 'http://keycloak:8080',
            'username' => $this->faker->userName,
            'realm' => $this->faker->slug
        ]);

        $this->expectException(RuntimeException::class);

        KeycloakAdmin::roles();
    }

    /**
     * @test
     */
    public function a_exception_is_thrown_when_the_realm_is_not_set_in_the_configuration()
    {
        $this->app['config']->set('keycloak-admin.connections.master', [
            'url' => 'http://keycloak:8080',
            'username' => $this->faker->userName,
            'password' => $this->faker->password
        ]);

        $this->expectException(RuntimeException::class);

        KeycloakAdmin::roles();
    }
}
