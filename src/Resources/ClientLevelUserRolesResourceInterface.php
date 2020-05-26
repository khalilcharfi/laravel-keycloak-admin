<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientLevelUserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function add(RoleRepresentationInterface $role);

    public function delete(RoleRepresentationInterface $role);
}
