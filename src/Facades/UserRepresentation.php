<?php
namespace Scito\Laravel\Keycloak\Admin\Facades;

use Illuminate\Support\Facades\Facade;
use Scito\Laravel\Keycloak\Admin\Builders\UserRepresentationBuilderAdapter;

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
