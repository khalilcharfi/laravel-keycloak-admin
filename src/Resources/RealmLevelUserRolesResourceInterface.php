<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface RealmLevelUserRolesResourceInterface
{
    public function add(RoleRepresentationInterface $role);

    public function remove(RoleRepresentationInterface $role);
    /*
    public function available();
    */
}
