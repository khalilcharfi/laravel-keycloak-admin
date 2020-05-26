<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

class CredentialRepresentation extends AbstractRepresentation
{
    /**
     * CredentialRepresentation constructor.
     * @param string $type
     * @param string $value
     * @param bool $temporary
     */
    public function __construct(string $type = null, string $value = null, bool $temporary = false)
    {
        $this->setAttributes(get_defined_vars());
    }

    public function getType(): string
    {
        return $this->getAttribute('type');
    }

    public function getValue(): string
    {
        return $this->getAttribute('value');
    }

    public function getTemporary(): ?bool
    {
        return $this->getAttribute('temporary');
    }
}
