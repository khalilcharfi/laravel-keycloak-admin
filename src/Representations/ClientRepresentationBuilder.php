<?php

namespace App\Laravel\Keycloak\Admin\Representations;

use App\Laravel\Keycloak\Admin\Hydrator\Hydrator;

class ClientRepresentationBuilder extends AbstractRepresentationBuilder implements ClientRepresentationBuilderInterface
{
    public function withClientId($clientId): ClientRepresentationBuilderInterface
    {
        return $this->setAttribute('clientId', $clientId);
    }

    public function build(): ClientRepresentationInterface
    {
        $data = $this->getAttributes();
        $hydrator = new Hydrator();
        return $hydrator->hydrate($data, ClientRepresentation::class);
    }
}
