<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;

interface UserResourceInterface
{
    public function toRepresentation(): UserRepresentationInterface;

    public function roles(): UserRolesResourceInterface;

    public function update(?array $options = null): UserUpdateResourceInterface;

    public function getRealm(): string;

    public function getId(): string;

    public function delete();
}
