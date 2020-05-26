<?php

namespace App\Laravel\Keycloak\Admin\Facades;

use App\Laravel\Keycloak\Admin\Builders\UserRepresentationBuilderAdapter;
use Illuminate\Support\Facades\Facade;

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
