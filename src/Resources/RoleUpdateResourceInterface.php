<?php

namespace App\Laravel\Keycloak\Admin\Resources;

interface RoleUpdateResourceInterface
{
    public function description(string $description): RoleUpdateResourceInterface;

    public function isClientRole(bool $clientRole): RoleUpdateResourceInterface;

    public function isComposite(bool $composite): RoleUpdateResourceInterface;

    public function containerId(string $containerId): RoleUpdateResourceInterface;

    public function save(): RoleResourceInterface;
}
