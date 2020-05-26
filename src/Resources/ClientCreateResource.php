<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\ClientRepresentationBuilderInterface;

class ClientCreateResource implements ClientCreateResourceInterface
{


    /**
     * @var ClientsResourceInterface
     */
    private $clientsResource;
    /**
     * @var ClientRepresentationBuilderInterface
     */
    private $builder;
    /**
     * @var string
     */
    private $realm;

    public function __construct(
        ClientsResourceInterface $clientsResource,
        ClientRepresentationBuilderInterface $builder,
        string $realm
    ) {
        $this->clientsResource = $clientsResource;
        $this->builder = $builder;
        $this->realm = $realm;
    }

    public function clientId(string $clientId): ClientCreateResourceInterface
    {
        $this->builder->withClientId($clientId);
        return $this;
    }

    public function save(): ClientResourceInterface
    {
        $client = $this->builder->build();
        return $this->clientsResource->add($client);
    }
}
