<?php

namespace App\Keycloak\Admin\Representations;

class RoleRepresentation extends AbstractRepresentation implements RoleRepresentationInterface
{
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $containerId = null,
        ?string $description = null,
        ?bool $composite = null,
        ?bool $clientRole = null,
        ?array $attributes = null
    ) {
        $this->setAttributes(get_defined_vars());
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    public function getDescription(): ?string
    {
        return $this->getAttribute('description');
    }

    public function getContainerId(): ?string
    {
        return $this->getAttribute('containerId');
    }

    public function isClientRole(): ?bool
    {
        return $this->getAttribute('clientRole');
    }

    public function isComposite(): ?bool
    {
        return $this->getAttribute('composite');
    }
}
