<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface RealmLevelUserRolesResourceInterface
{
    public function add(RoleRepresentationInterface $role);

    public function remove(RoleRepresentationInterface $role);
    /*
    public function available();
    */
}
