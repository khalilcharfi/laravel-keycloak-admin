<?php

namespace App\Laravel\Keycloak\Admin\Representations;

class ClientRepresentation extends AbstractRepresentation implements ClientRepresentationInterface
{
    public function __construct(
        ?string $id = null,
        ?string $clientId = null,
        ?string $name = null
    )
    {
        $this->setAttributes(get_defined_vars());
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function getClientId(): ?string
    {
        return $this->getAttribute('clientId');
    }

    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }
}
