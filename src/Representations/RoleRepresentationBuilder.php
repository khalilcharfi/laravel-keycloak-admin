<?php

namespace App\Keycloak\Admin\Representations;

use App\Keycloak\Admin\Hydrator\Hydrator;

class RoleRepresentationBuilder extends AbstractRepresentationBuilder implements RoleRepresentationBuilderInterface
{
    public function withName(string $name): RoleRepresentationBuilderInterface
    {
        return $this->setAttribute('name', $name);
    }

    public function withDescription(string $description): RoleRepresentationBuilderInterface
    {
        return $this->setAttribute('description', $description);
    }

    public function withClientRole(bool $isClientRole): RoleRepresentationBuilderInterface
    {
        return $this->setAttribute('clientRole', $isClientRole);
    }

    public function withComposite(bool $composite): RoleRepresentationBuilderInterface
    {
        return $this->setAttribute('composite', $composite);
    }

    public function withContainerId(string $containerId): RoleRepresentationBuilderInterface
    {
        return $this->setAttribute('containerId', $containerId);
    }

    public function build(): RoleRepresentationInterface
    {
        $data = $this->getAttributes();
        $hydrator = new Hydrator();
        return $hydrator->hydrate($data, RoleRepresentation::class);
    }
}
