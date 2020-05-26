<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Facades;

use Illuminate\Support\Facades\Facade;
use Kcharfi\Laravel\Keycloak\Admin\Client;
use Kcharfi\Laravel\Keycloak\Admin\Resources\ClientResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\ClientsResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RealmResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RealmsResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RolesResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\UserResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\UsersResourceInterface;

/**
 * @method static UsersResourceInterface users()
 * @method static UserResourceInterface user(string $id)
 * @method static RolesResourceInterface roles()
 * @method static ClientsResourceInterface clients()
 * @method static Client connection(?string $client)
 * @method static RealmResourceInterface realm(?string $realm = null)
 * @method static ClientResourceInterface client(?string $id = null)
 * @method static RealmsResourceInterface realms()
 *
 * @package Keycloak\Admin\Facades
 */
class KeycloakAdmin extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'keycloak-admin.client';
    }
}
