<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface RolesResourceInterface
{
    /**
     * @return RepresentationCollectionInterface
     */
    public function all(): RepresentationCollectionInterface;

    public function add(RoleRepresentationInterface $role): RoleResourceInterface;

    public function create(?array $options = null): RoleCreateResourceInterface;

    public function update(RoleRepresentationInterface $role): RoleResourceInterface;

    /**
     * @param string $id
     * @return RoleResourceInterface
     */
    public function get($id): RoleResourceInterface;

    /**
     * @param string $name
     * @return RoleResourceInterface
     */
    public function getByName($name): RoleResourceInterface;

    /**
     * @param string $id
     */
    public function delete(string $id): void;

    /**
     * @param string $name
     */
    public function deleteByName(string $name): void;
}
