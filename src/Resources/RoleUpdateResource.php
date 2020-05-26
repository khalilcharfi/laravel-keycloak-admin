<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationBuilderInterface;
use RuntimeException;

class RoleUpdateResource implements RoleUpdateResourceInterface
{
    /**
     * @var RolesResourceInterface
     */
    private $rolesResource;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var string
     */
    private $role;
    /**
     * @var RoleRepresentationBuilderInterface
     */
    private $builder;

    public function __construct(
        RolesResourceInterface $rolesResource,
        RoleRepresentationBuilderInterface $builder,
        string $realm,
        string $role
    ) {
        $this->rolesResource = $rolesResource;
        $this->realm = $realm;
        $this->role = $role;
        $this->builder = $builder;
    }

    public function __call($name, $arguments)
    {
        throw new RuntimeException("Unknown updateable role property [$name]");
    }

    public function save(): RoleResourceInterface
    {
        $this->builder->withName($this->role);
        return $this->rolesResource->update($this->builder->build());
    }

    public function description(string $description): RoleUpdateResourceInterface
    {
        $this->builder->withDescription($description);
        return $this;
    }

    public function isClientRole(bool $clientRole): RoleUpdateResourceInterface
    {
        $this->builder->withClientRole($clientRole);
        return $this;
    }

    public function isComposite(bool $composite): RoleUpdateResourceInterface
    {
        $this->builder->withComposite($composite);
        return $this;
    }

    public function containerId(string $containerId): RoleUpdateResourceInterface
    {
        $this->builder->withContainerId($containerId);
        return $this;
    }
}
