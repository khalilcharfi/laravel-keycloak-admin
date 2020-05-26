<?php

namespace App\Keycloak\Admin\Resources;

interface RoleCreateResourceInterface
{
    public function name(string $name): RoleCreateResourceInterface;

    public function description(string $description): RoleCreateResourceInterface;

    public function containerId(string $containerId): RoleCreateResourceInterface;

    public function composite(bool $composite): RoleCreateResourceInterface;

    public function clientRole(bool $clientRole): RoleCreateResourceInterface;

    public function save(): RoleResourceInterface;
}
