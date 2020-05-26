<?php

namespace App\Laravel\Keycloak\Admin\Facades;

use App\Laravel\Keycloak\Admin\Builders\RoleRepresentationBuilderAdapter;
use Illuminate\Support\Facades\Facade;

/**
 * Class UserRepresentation
 *
 *
 * @mixin RoleRepresentationBuilderAdapter
 * @package Keycloak\Admin\Facades
 */
class RoleRepresentation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'keycloak-admin.role-representation-builder';
    }
}
