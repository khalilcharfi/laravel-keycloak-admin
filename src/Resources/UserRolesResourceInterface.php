<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RepresentationCollectionInterface;

interface UserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function realm(): RealmLevelUserRolesResourceInterface;

    public function client(string $id): ClientLevelUserRolesResourceInterface;
}
