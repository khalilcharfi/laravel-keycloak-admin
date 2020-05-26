<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientLevelUserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function add(RoleRepresentationInterface $role);

    public function delete(RoleRepresentationInterface $role);
}
