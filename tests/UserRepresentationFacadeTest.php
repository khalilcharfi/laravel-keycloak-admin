<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Kcharfi\Laravel\Keycloak\Admin\Facades\UserRepresentation;
use Kcharfi\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;

class UserRepresentationFacadeTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function the_user_representation_facade_creates_an_instance()
    {

        $password = $this->faker->password;
        $username = $this->faker->userName;
        $email = $this->faker->email;

        $user = UserRepresentation
            ::password($password)
            ->username($username)
            ->email($email)
            ->build();

        $this->assertInstanceOf(UserRepresentationInterface::class, $user);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
    }
}
