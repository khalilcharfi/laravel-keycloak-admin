<?php

namespace App\Keycloak\Admin\Tests\Browser;

use App\Keycloak\Admin\Tests\TestCase;
use App\Keycloak\Admin\Tests\Traits\WithDuskBrowser;
use App\Keycloak\Admin\Tests\Traits\WithTemporaryRealm;
use Laravel\Dusk\Browser;

/**
 * Class LoginTest
 * @package Keycloak\Admin\Tests\Browser
 * @group browser
 */
class LoginTest extends TestCase
{
    use WithTemporaryRealm, WithDuskBrowser;

    /**
     * @test
     */
    public function a_user_can_login_with_the_password_which_was_used_during_creation()
    {

        $username = $this->faker->userName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->client
            ->realm($this->temporaryRealm)
            ->users()
            ->create()
            ->username($username)
            ->password($password)
            ->email($email)
            ->enabled(true)
            ->save();

        $this->browse(function (Browser $browser) use ($username, $email, $password) {
            $browser->visit("http://keycloak:8080/auth/realms/{$this->temporaryRealm}/account")
                ->type('username', $username)
                ->type('password', $password)
                ->press('login')
                ->assertInputValue('#email', $email);
        });
    }

    /**
     * @test
     */
    public function a_user_must_change_his_password_when_a_temporary_password_was_configured()
    {

        $username = $this->faker->userName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->client
            ->realm($this->temporaryRealm)
            ->users()
            ->create()
            ->username($username)
            ->temporaryPassword($password)
            ->email($email)
            ->enabled(true)
            ->save();

        $this->browse(function (Browser $browser) use ($username, $email, $password) {
            $browser->visit("http://keycloak:8080/auth/realms/{$this->temporaryRealm}/account")
                ->type('username', $username)
                ->type('password', $password)
                ->press('login')
                ->assertSee('You need to change your password');
        });
    }
}
