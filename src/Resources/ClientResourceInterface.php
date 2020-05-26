<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\ClientRepresentationInterface;

interface ClientResourceInterface
{
    public function toRepresentation(): ClientRepresentationInterface;

    public function roles(): ClientRolesResourceInterface;

    public function getId(): string;

    public function getRealm(): string;
}
