<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface RealmLevelUserRolesResourceInterface
{
    public function add(RoleRepresentationInterface $role);

    public function remove(RoleRepresentationInterface $role);
    /*
    public function available();
    */
}
