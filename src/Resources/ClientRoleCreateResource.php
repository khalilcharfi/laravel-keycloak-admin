<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RoleRepresentationBuilderInterface;

class ClientRoleCreateResource implements ClientRoleCreateResourceInterface
{
    /**
     * @var ClientRolesResourceInterface
     */
    private $clientRolesResource;
    /**
     * @var RoleRepresentationBuilderInterface
     */
    private $builder;

    public function __construct(
        ClientRolesResourceInterface $clientRolesResource,
        RoleRepresentationBuilderInterface $builder
    ) {
        $this->clientRolesResource = $clientRolesResource;
        $this->builder = $builder;
    }

    public function name(string $name): ClientRoleCreateResourceInterface
    {
        $this->builder->withName($name);
        return $this;
    }

    public function description(string $description): ClientRoleCreateResourceInterface
    {
        $this->builder->withDescription($description);
        return $this;
    }

    public function save(): ClientRoleResourceInterface
    {
        $this->builder->withClientRole(true);
        return $this->clientRolesResource->add($this->builder->build());
    }
}
