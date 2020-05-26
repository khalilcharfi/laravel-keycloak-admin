<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\ClientRepresentationBuilder;
use App\Laravel\Keycloak\Admin\Representations\RealmRepresentationBuilder;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationBuilder;
use App\Laravel\Keycloak\Admin\Representations\UserRepresentationBuilder;
use GuzzleHttp\ClientInterface;

class ResourceFactory implements ResourceFactoryInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(ClientInterface $client, HydratorInterface $hydrator)
    {
        $this->client = $client;
        $this->hydrator = $hydrator;
    }

    public function createRealmCreateResource(): RealmCreateResourceInterface
    {
        return new RealmCreateResource($this->createRealmsResource(), new RealmRepresentationBuilder());
    }

    public function createRealmsResource(): RealmsResourceInterface
    {
        return new RealmsResource($this->client, $this, $this->hydrator);
    }

    public function createUserRolesResource(string $realm, string $id): UserRolesResourceInterface
    {
        return new UserRolesResource($this, $this->hydrator, $this->client, $realm, $id);
    }

    public function createUserResource(string $realm, string $id): UserResourceInterface
    {
        return new UserResource($this->client, $this, $this->hydrator, $realm, $id);
    }

    public function createRealmResource(string $realm): RealmResourceInterface
    {
        return new RealmResource($this->client, $this, $this->hydrator, $realm);
    }

    public function createClientRoleCreateResource(string $realm, string $id): ClientRoleCreateResourceInterface
    {
        return new ClientRoleCreateResource(
            $this->createClientRolesResource($realm, $id),
            new RoleRepresentationBuilder()
        );
    }

    public function createClientRolesResource(string $realm, string $id): ClientRolesResourceInterface
    {
        return new ClientRolesResource($this, $this->client, $this->hydrator, $realm, $id);
    }

    public function createClientResource(string $realm, string $id): ClientResourceInterface
    {
        return new ClientResource($this, $this->client, $this->hydrator, $realm, $id);
    }

    public function createClientRoleResource(string $realm, string $id, string $name): ClientRoleResourceInterface
    {
        return new ClientRoleResource($this->client, $this->hydrator, $realm, $id, $name);
    }

    public function createUserSearchResource(string $realm): UserSearchResourceInterface
    {
        return new UserSearchResource($this->client, $this, $this->hydrator, $realm);
    }

    public function createUserCreateResource(string $realm): UserCreateResourceInterface
    {
        $usersResource = $this->createUsersResource($realm);
        return new UserCreateResource($usersResource, new UserRepresentationBuilder());
    }

    public function createUsersResource(string $realm): UsersResourceInterface
    {
        return new UsersResource($this->client, $this, $this->hydrator, $realm);
    }

    public function createUserUpdateResource(string $realm, string $id): UserUpdateResourceInterface
    {
        $usersResource = $this->createUsersResource($realm);
        return new UserUpdateResource($usersResource, new UserRepresentationBuilder(), $realm, $id);
    }

    public function createRealmLevelUserRolesResource(string $realm, string $id): RealmLevelUserRolesResourceInterface
    {
        return new RealmLevelUserRolesResource($this->client, $this, $this->hydrator, $realm, $id);
    }

    public function createClientLevelUserRolesResource(
        string $realm,
        string $userId,
        string $clientId
    ): ClientLevelUserRolesResourceInterface {
        return new ClientLevelUserRolesResource($this->client, $this->hydrator, $realm, $userId, $clientId);
    }

    public function createClientCreateResource(string $realm): ClientCreateResourceInterface
    {
        return new ClientCreateResource(
            $this->createClientsResource($realm),
            new ClientRepresentationBuilder(),
            $realm
        );
    }

    public function createClientsResource(string $realm): ClientsResourceInterface
    {
        return new ClientsResource($this, $this->client, $this->hydrator, $realm);
    }

    public function createRoleResource(string $realm, string $role)
    {
        return new RoleResource($this->client, $this, $this->hydrator, $realm, $role);
    }

    public function createRolesCreateResource(string $realm): RoleCreateResourceInterface
    {
        return new RoleCreateResource($this->createRolesResource($realm), new RoleRepresentationBuilder(), $realm);
    }

    public function createRolesResource(string $realm): RolesResourceInterface
    {
        return new RolesResource($this->client, $this, $this->hydrator, $realm);
    }

    public function createRoleUpdateResource(string $realm, string $role): RoleUpdateResourceInterface
    {
        return new RoleUpdateResource(
            $this->createRolesResource($realm),
            new RoleRepresentationBuilder(),
            $realm,
            $role
        );
    }
}
