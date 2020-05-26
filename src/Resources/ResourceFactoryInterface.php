<?php

namespace App\Laravel\Keycloak\Admin\Resources;

interface ResourceFactoryInterface
{
    public function createRealmsResource(): RealmsResourceInterface;

    public function createRealmCreateResource(): RealmCreateResourceInterface;

    /**
     * @param string $realm
     * @return UsersResourceInterface
     */
    public function createUsersResource(string $realm): UsersResourceInterface;

    /**
     * @param string $realm
     * @param string $id
     * @return UserResourceInterface
     */
    public function createUserResource(string $realm, string $id): UserResourceInterface;

    /**
     * @param string $realm
     * @return RealmResourceInterface
     */
    public function createRealmResource(string $realm): RealmResourceInterface;

    /**
     * @param string $realm
     * @return ClientsResourceInterface
     */
    public function createClientsResource(string $realm): ClientsResourceInterface;

    public function createClientResource(string $realm, string $id): ClientResourceInterface;

    public function createClientCreateResource(string $realm): ClientCreateResourceInterface;

    public function createClientRolesResource(string $realm, string $id): ClientRolesResourceInterface;

    public function createClientRoleCreateResource(string $realm, string $id): ClientRoleCreateResourceInterface;

    public function createClientRoleResource(string $realm, string $id, string $name): ClientRoleResourceInterface;

    /**
     * @param string $realm
     * @return UserSearchResourceInterface
     */
    public function createUserSearchResource(string $realm): UserSearchResourceInterface;

    /**
     * @param string $realm
     * @return UserCreateResourceInterface
     */
    public function createUserCreateResource(string $realm): UserCreateResourceInterface;

    /**
     * @param string $realm
     * @param string $id
     * @return UserUpdateResourceInterface
     */
    public function createUserUpdateResource(string $realm, string $id): UserUpdateResourceInterface;

    /**
     * @param string $realm
     * @param string $id
     * @return UserRolesResourceInterface
     */
    public function createUserRolesResource(string $realm, string $id): UserRolesResourceInterface;

    public function createClientLevelUserRolesResource(
        string $realm,
        string $userId,
        string $clientId
    ): ClientLevelUserRolesResourceInterface;

    public function createRealmLevelUserRolesResource(
        string $realm,
        string $userId
    ): RealmLevelUserRolesResourceInterface;

    /**
     * @param string $realm
     * @param string $role
     * @return RoleResourceInterface
     */
    public function createRoleResource(string $realm, string $role);

    /**
     * @param string $realm
     * @return RolesResourceInterface
     */
    public function createRolesResource(string $realm): RolesResourceInterface;

    /**
     * @param string $realm
     * @return RoleCreateResourceInterface
     */
    public function createRolesCreateResource(string $realm): RoleCreateResourceInterface;

    /**
     * @param string $realm
     * @param string $role
     * @return RoleUpdateResourceInterface
     */
    public function createRoleUpdateResource(string $realm, string $role): RoleUpdateResourceInterface;
}
