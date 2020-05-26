<?php

namespace App\Laravel\Keycloak\Admin\Resources;

/**
 * Interface RealmCreateResourceInterface
 *
 * @package Keycloak\Admin\Resources
 */
interface RealmCreateResourceInterface
{
    public function name(string $name): RealmCreateResourceInterface;

    public function displayName(string $name): RealmCreateResourceInterface;

    public function enabled(bool $enabled): RealmCreateResourceInterface;

    public function save(): void;
}
