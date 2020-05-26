<?php

namespace App\Laravel\Keycloak\Admin\Tests\Traits;

use Faker\Generator;

trait WithTemporaryRealm
{
    use WithFaker, WithTestClient;

    /**
     * @var Generator
     */
    protected $temporaryRealm;

    /**
     * @before
     */
    public function setupTemporaryRealmClass()
    {
        $this->temporaryRealm = $this->faker->userName;
        $this->client
            ->realms()
            ->create()
            ->name($this->temporaryRealm)
            ->enabled(true)
            ->save();
    }

    /**
     * @after
     */
    public function teardownTemporaryRealmClass()
    {
        $this->client
            ->realm($this->temporaryRealm)
            ->delete();
        $this->temporaryRealm = null;
    }
}
