<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

use DateTime;
use function get_defined_vars;

class UserRepresentation extends AbstractRepresentation implements UserRepresentationInterface
{
    /**
     * User constructor.
     *
     * @param string $id
     * @param string $username
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param CredentialRepresentation[] $credentials
     * @param bool $enabled
     * @param bool $emailVerified
     * @param Attribute[] $attributes
     * @param string[] $requiredActions
     * @param DateTime $created
     */
    public function __construct(
        string $id = null,
        string $username = null,
        string $email = null,
        string $firstName = null,
        string $lastName = null,
        array $credentials = [],
        bool $enabled = false,
        bool $emailVerified = false,
        array $attributes = [],
        array $requiredActions = [],
        DateTime $created = null
    ) {
        $this->setAttributes(get_defined_vars());
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function getEmail(): ?string
    {
        return $this->getAttribute('email');
    }

    public function getUsername(): ?string
    {
        return $this->getAttribute('username');
    }

    public function getFirstName(): ?string
    {
        return $this->getAttribute('firstName');
    }

    public function getLastName(): ?string
    {
        return $this->getAttribute('lastName');
    }

    public function getEnabled(): ?bool
    {
        return $this->getAttribute('enabled', false);
    }

    public function getCreated()
    {
        return $this->getAttribute('created');
    }

    public function getCredentials(): array
    {
        return $this->getAttribute('credentials', []);
    }

    public function getRequiredActions()
    {
        return $this->getAttribute('requiredActions', []);
    }
}
