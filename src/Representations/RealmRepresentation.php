<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

class RealmRepresentation extends AbstractRepresentation implements RealmRepresentationInterface
{
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $displayName = null,
        ?bool $enabled = null
    )
    {
        $this->setAttributes(get_defined_vars());
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function getName()
    {
        return $this->getAttribute('name');
    }

    public function getEnabled()
    {
        return $this->getAttribute('enabled', false);
    }

    public function getDisplayName(): string
    {
        return $this->getAttribute('displayName');
    }
}
