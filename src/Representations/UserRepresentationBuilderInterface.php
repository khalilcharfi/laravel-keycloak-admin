<?php

namespace App\Keycloak\Admin\Representations;

interface UserRepresentationBuilderInterface
{
    public function withId(string $id): self;

    public function withUsername(string $username): self;

    public function withEmail(string $email): self;

    public function withPassword(string $password): self;

    public function withFirstName(string $firstName): self;

    public function withLastName(string $lastName): self;

    public function withTemporaryPassword(string $password): self;

    public function withPasswordIsTemporary(bool $temporary): self;

    public function withRequiredActions(?array $actions): self;

    public function withEnabled(bool $enabled): self;

    public function build(): UserRepresentationInterface;
}
