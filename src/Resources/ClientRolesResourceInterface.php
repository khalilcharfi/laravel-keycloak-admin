<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function create(?array $options = null): ClientRoleCreateResourceInterface;

    public function add(RoleRepresentationInterface $role);
}
