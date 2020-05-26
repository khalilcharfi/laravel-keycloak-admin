<?php

namespace App\Laravel\Keycloak\Admin\Tests;

use App\Laravel\Keycloak\Admin\Representations\RealmRepresentationInterface;
use App\Laravel\Keycloak\Admin\Tests\Traits\WithTemporaryRealm;

class RealmResourceTest extends TestCase
{
    use WithTemporaryRealm;

    /**
     * @test
     */
    public function realm_details_can_be_retrieved()
    {
        $realm = $this->client->realm($this->temporaryRealm)->toRepresentation();
        $this->assertInstanceOf(RealmRepresentationInterface::class, $realm);
    }

    /**
     * @test
     */
    public function realms_can_be_created()
    {

        $name = $this->faker->name;

        $this->client->realms()
            ->create()
            ->name($name)
            ->save();

        $realm = $this
            ->client
            ->realm($name)
            ->toRepresentation();

        $this->assertInstanceOf(RealmRepresentationInterface::class, $realm);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
