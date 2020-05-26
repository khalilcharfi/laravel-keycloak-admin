<?php

namespace App\Keycloak\Admin\Resources;

interface ClientRoleCreateResourceInterface
{
    public function name(string $name): ClientRoleCreateResourceInterface;

    public function description(string $description): ClientRoleCreateResourceInterface;

    public function save(): ClientRoleResourceInterface;
}
