<?php

namespace App\Laravel\Keycloak\Admin\Tests;

use App\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;
use App\Laravel\Keycloak\Admin\Facades\UserRepresentation;
use Illuminate\Foundation\Testing\WithFaker;

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