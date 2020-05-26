<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

interface UserRolesScopeResourceInterface
{
    /**
     * @return RepresentationCollectionInterface
     */
    public function all(): RepresentationCollectionInterface;

    /**
     * @return RepresentationCollectionInterface
     */
    public function available(): RepresentationCollectionInterface;

    /**
     * @return RepresentationCollectionInterface
     */
    public function effective(): RepresentationCollectionInterface;

    public function add(RoleRepresentationInterface $role);

    public function remove(RoleRepresentationInterface $roles);
}
