<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface ClientLevelUserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function add(RoleRepresentationInterface $role);

    public function delete(RoleRepresentationInterface $role);
}
