<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use GuzzleHttp\ClientInterface;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotCreateClientException;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveClientsException;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\ClientRepresentation;
use Kcharfi\Laravel\Keycloak\Admin\Representations\ClientRepresentationInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;

class ClientsResource implements ClientsResourceInterface
{
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;
    /**
     * @var ClientInterface
     */
    private $client;

    private $realm;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        ClientInterface $client,
        HydratorInterface $hydrator,
        string $realm
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->client = $client;
        $this->realm = $realm;
        $this->hydrator = $hydrator;
    }

    public function add(ClientRepresentationInterface $client): ClientResourceInterface
    {
        $data = $this->hydrator->extract($client);
        unset($data['id']);
        $response = $this->client->post("/auth/admin/realms/{$this->realm}/clients", ['body' => json_encode($data)]);

        if (201 !== $response->getStatusCode()) {
            throw new CannotCreateClientException();
        }
        $location = $response->getHeaderLine('Location');
        $parts = array_filter(explode('/', $location), 'strlen');
        $id = end($parts);
        return $this->get($id);
    }

    public function get(string $id): ClientResourceInterface
    {
        return $this->resourceFactory->createClientResource($this->realm, $id);
    }

    public function create(): ClientCreateResourceInterface
    {
        return $this->resourceFactory->createClientCreateResource($this->realm);
    }

    public function all(): RepresentationCollectionInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/clients");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveClientsException();
        }

        $json = (string)$response->getBody();

        $clients = json_decode($json, true);

        $items = array_map(function ($client) {
            return $this->hydrator->hydrate($client, ClientRepresentation::class);
        }, $clients);

        return new RepresentationCollection($items);
    }
}
