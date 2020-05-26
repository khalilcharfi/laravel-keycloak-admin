<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\UserRepresentationInterface;

interface UsersResourceInterface
{
    /**
     * @param $id
     * @return UserResourceInterface|null
     */
    public function get($id): UserResourceInterface;

    public function getByEmail(string $email): ?UserResourceInterface;

    public function getByUsername(string $username): ?UserResourceInterface;

    public function add(UserRepresentationInterface $user): UserResourceInterface;

    public function update(UserRepresentationInterface $user): void;

    /**
     * @param array $options
     * @return UserSearchResourceInterface
     */
    public function search(?array $options = null): UserSearchResourceInterface;

    public function create(?array $options = null): UserCreateResourceInterface;
}
