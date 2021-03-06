<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface RoleResourceInterface
{
    /**
     * @return RoleRepresentationInterface
     */
    public function toRepresentation(): RoleRepresentationInterface;

    /**
     * Delete the current role
     */
    public function delete(): void;

    public function update(?array $options = null): RoleUpdateResourceInterface;

    public function getRealm(): string;

    public function getId(): string;

    public function getName(): string;
}
