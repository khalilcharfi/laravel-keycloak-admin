<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RoleRepresentationBuilderInterface;

class RoleCreateResource implements RoleCreateResourceInterface
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
     * @var RoleRepresentationBuilderInterface
     */
    private $builder;

    public function __construct(
        RolesResourceInterface $rolesResource,
        RoleRepresentationBuilderInterface $builder,
        string $realm
    ) {
        $this->rolesResource = $rolesResource;
        $this->realm = $realm;
        $this->builder = $builder;
    }

    public function name(string $name): RoleCreateResourceInterface
    {
        $this->builder->withName($name);
        return $this;
    }

    public function description(string $description): RoleCreateResourceInterface
    {
        $this->builder->withDescription($description);
        return $this;
    }

    public function composite(bool $composite): RoleCreateResourceInterface
    {
        $this->builder->withComposite($composite);
        return $this;
    }

    public function containerId(string $containerId): RoleCreateResourceInterface
    {
        $this->builder->withContainerId($containerId);
        return $this;
    }

    public function clientRole(bool $clientRole): RoleCreateResourceInterface
    {
        $this->builder->withClientRole($clientRole);
        return $this;
    }

    public function save(): RoleResourceInterface
    {
        return $this->rolesResource->add($this->builder->build());
    }
}
