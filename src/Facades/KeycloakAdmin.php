<?php

namespace App\Laravel\Keycloak\Admin\Facades;

use App\Laravel\Keycloak\Admin\Client;
use App\Laravel\Keycloak\Admin\Resources\ClientResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\ClientsResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\RealmResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\RealmsResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\RolesResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\UserResourceInterface;
use App\Laravel\Keycloak\Admin\Resources\UsersResourceInterface;
use Illuminate\Support\Facades\Facade;

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
