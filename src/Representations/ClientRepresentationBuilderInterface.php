<?php

namespace App\Keycloak\Admin\Representations;

interface ClientRepresentationBuilderInterface
{
    public function withClientId($clientId): ClientRepresentationBuilderInterface;

    public function build(): ClientRepresentationInterface;
}
