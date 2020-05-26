<?php

namespace App\Keycloak\Admin\Representations;

interface UserRepresentationInterface extends RepresentationInterface
{
    public function getId(): ?string;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function getFirstName(): ?string;

    public function getLastName(): ?string;
}
