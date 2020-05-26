<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;

interface UserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function realm(): RealmLevelUserRolesResourceInterface;

    public function client(string $id): ClientLevelUserRolesResourceInterface;
}
