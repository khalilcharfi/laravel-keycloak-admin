<?php
namespace Scito\Laravel\Keycloak\Admin\Facades;

use Illuminate\Support\Facades\Facade;
use Scito\Keycloak\Admin\Resources\ClientResourceInterface;
use Scito\Keycloak\Admin\Resources\ClientsResourceInterface;
use Scito\Keycloak\Admin\Resources\RealmResourceInterface;
use Scito\Keycloak\Admin\Resources\RealmsResourceInterface;
use Scito\Keycloak\Admin\Resources\RolesResourceInterface;
use Scito\Keycloak\Admin\Resources\UserResourceInterface;
use Scito\Keycloak\Admin\Client;
use Scito\Keycloak\Admin\Resources\UsersResourceInterface;

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
