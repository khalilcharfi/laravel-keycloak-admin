<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Facades;

use Illuminate\Support\Facades\Facade;
use Kcharfi\Laravel\Keycloak\Admin\Builders\RoleRepresentationBuilderAdapter;

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
