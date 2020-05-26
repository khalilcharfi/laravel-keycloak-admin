<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveClientException;
use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\ClientRepresentation;
use App\Laravel\Keycloak\Admin\Representations\ClientRepresentationInterface;
use GuzzleHttp\ClientInterface;

class ClientResource implements ClientResourceInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var string
     */
    private $id;
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        ClientInterface $client,
        HydratorInterface $hydrator,
        string $realm,
        string $id
    ) {
        $this->client = $client;
        $this->realm = $realm;
        $this->id = $id;
        $this->hydrator = $hydrator;
        $this->resourceFactory = $resourceFactory;
    }

    public function roles(): ClientRolesResourceInterface
    {
        return $this->resourceFactory->createClientRolesResource($this->realm, $this->id);
    }

    public function toRepresentation(): ClientRepresentationInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/clients/{$this->id}");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveClientException();
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);

        return $this->hydrator->hydrate($data, ClientRepresentation::class);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRealm(): string
    {
        return $this->realm;
    }
}
