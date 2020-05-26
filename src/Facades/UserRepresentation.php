<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Facades;

use Illuminate\Support\Facades\Facade;
use Kcharfi\Laravel\Keycloak\Admin\Builders\UserRepresentationBuilderAdapter;

/**
 * Class UserRepresentation
 *
 *
 * @mixin UserRepresentationBuilderAdapter
 * @package Keycloak\Admin\Facades
 */
class UserRepresentation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'keycloak-admin.user-representation-builder';
    }
}
