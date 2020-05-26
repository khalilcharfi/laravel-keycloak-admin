<?php

namespace App\Keycloak\Admin\Representations;

interface ClientRepresentationInterface extends RepresentationInterface
{
    public function getId(): ?string;

    public function getClientId(): ?string;

    public function getName(): ?string;
}
