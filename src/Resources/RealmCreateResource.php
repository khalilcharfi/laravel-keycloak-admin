<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\RealmRepresentationBuilder;

class RealmCreateResource implements RealmCreateResourceInterface
{
    /**
     * @var RealmsResourceInterface
     */
    private $realmsResource;
    /**
     * @var RealmRepresentationBuilder
     */
    private $builder;

    public function __construct(
        RealmsResourceInterface $realmsResource,
        RealmRepresentationBuilder $builder
    ) {
        $this->realmsResource = $realmsResource;
        $this->builder = $builder;
    }

    public function name(string $name): RealmCreateResourceInterface
    {
        $this->builder->withName($name);
        return $this;
    }

    public function displayName(string $name): RealmCreateResourceInterface
    {
        $this->builder->withDisplayName($name);
        return $this;
    }

    public function enabled(bool $enabled): RealmCreateResourceInterface
    {
        $this->builder->withEnabled($enabled);
        return $this;
    }

    public function save(): void
    {
        $this->realmsResource->add($this->builder->build());
    }
}
