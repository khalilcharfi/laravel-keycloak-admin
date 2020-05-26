<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function create(?array $options = null): ClientRoleCreateResourceInterface;

    public function add(RoleRepresentationInterface $role);
}
