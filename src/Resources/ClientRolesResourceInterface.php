<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function create(?array $options = null): ClientRoleCreateResourceInterface;

    public function add(RoleRepresentationInterface $role);
}
