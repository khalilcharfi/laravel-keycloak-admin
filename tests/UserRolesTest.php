<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Tests;

use Kcharfi\Laravel\Keycloak\Admin\Representations\ClientRepresentationInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RealmResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Tests\Traits\WithTemporaryRealm;

class UserRolesTest extends TestCase
{
    use WithTemporaryRealm;


    /**
     * @var RealmResourceInterface
     */
    private $realmResource;

    /**
     * @test
     */
    public function client_level_roles_can_be_retrieved()
    {
        $userResource = $this
            ->client
            ->realm('master')
            ->users();

        $user = $userResource
            ->search()
            ->username('admin')
            ->get()
            ->first();

        if (!$user instanceof UserRepresentationInterface) {
            throw new \RuntimeException("Admin user does not exist");
        }

        $client = $this->client
            ->realm('master')
            ->clients()
            ->all()
            ->first(function (ClientRepresentationInterface $client) {
                return 'account' === $client->getClientId();
            });

        if (!$client instanceof ClientRepresentationInterface) {
            throw new \RuntimeException("Cannot find client 'account'");
        }

        $id = $user->getId();

        $role = $userResource
            ->get($id)
            ->roles()
            ->client($client->getId())
            ->all()
            ->first(function (RoleRepresentationInterface $role) {
                return 'manage-account' === $role->getName();
            });

        $this->assertInstanceOf(RoleRepresentationInterface::class, $role);
    }

    /**
     * @test
     */
    public function client_level_roles_can_be_added()
    {

        $username = $this->faker->userName;
        $email = $this->faker->email;
        $password = $this->faker->password;
        $clientId = $this->faker->slug;
        $roleName = $this->faker->slug;

        $user = $this->realmResource
            ->users()
            ->create([
                'username' => $username,
                'email' => $email,
                'password' => $password
            ])
            ->save()
            ->toRepresentation();

        // Create a new client
        $client = $this->realmResource
            ->clients()
            ->create()
            ->clientId($clientId)
            ->save()
            ->toRepresentation();

        $role = $this->realmResource
            ->clients()
            ->get($client->getId())
            ->roles()
            ->create()
            ->name($roleName)
            ->save()
            ->toRepresentation();

        $this->realmResource
            ->users()
            ->get($user->getId())
            ->roles()
            ->client($client->getId())
            ->add($role);

        // Check if the user has the role
        $addedRole = $this->realmResource
            ->users()
            ->get($user->getId())
            ->roles()
            ->client($client->getId())
            ->all()
            ->first(function (RoleRepresentationInterface $role) use ($roleName) {
                return $roleName === $role->getName();
            });

        $this->assertInstanceOf(RoleRepresentationInterface::class, $addedRole);
    }

    /**
     * @test
     */
    public function client_level_roles_can_be_deleted()
    {

        $username = $this->faker->userName;
        $email = $this->faker->email;
        $password = $this->faker->password;
        $clientId = $this->faker->slug;
        $roleName = $this->faker->slug;

        $user = $this->realmResource
            ->users()
            ->create([
                'username' => $username,
                'email' => $email,
                'password' => $password
            ])
            ->save()
            ->toRepresentation();

        // Create a new client
        $client = $this->realmResource
            ->clients()
            ->create()
            ->clientId($clientId)
            ->save()
            ->toRepresentation();

        $role = $this->realmResource
            ->clients()
            ->get($client->getId())
            ->roles()
            ->create()
            ->name($roleName)
            ->save()
            ->toRepresentation();

        $this->realmResource
            ->users()
            ->get($user->getId())
            ->roles()
            ->client($client->getId())
            ->add($role);

        $this->realmResource
            ->users()
            ->get($user->getId())
            ->roles()
            ->client($client->getId())
            ->delete($role);

        // Check if the user has the role
        $addedRole = $this->realmResource
            ->users()
            ->get($user->getId())
            ->roles()
            ->client($client->getId())
            ->all()
            ->first(function (RoleRepresentationInterface $role) use ($roleName) {
                return $roleName === $role->getName();
            });

        $this->assertNull($addedRole);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->realmResource = $this->client->realm($this->temporaryRealm);
    }
}
