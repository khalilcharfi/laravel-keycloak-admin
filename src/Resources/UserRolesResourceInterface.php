<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;

interface UserRolesResourceInterface
{
    public function all(): RepresentationCollectionInterface;

    public function realm(): RealmLevelUserRolesResourceInterface;

    public function client(string $id): ClientLevelUserRolesResourceInterface;
}
