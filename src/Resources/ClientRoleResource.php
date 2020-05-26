<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use GuzzleHttp\ClientInterface;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentation;

class ClientRoleResource implements ClientRoleResourceInterface
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
     * @var string
     */
    private $name;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ClientInterface $client,
        HydratorInterface $hydrator,
        string $realm,
        string $id,
        string $name
    ) {
        $this->client = $client;
        $this->realm = $realm;
        $this->id = $id;
        $this->name = $name;
        $this->hydrator = $hydrator;
    }

    public function toRepresentation()
    {
        $url = "/auth/admin/realms/{$this->realm}/clients/{$this->id}/roles/{$this->name}";
        $response = $this->client->get($url);

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveRoleException();
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);
        return $this->hydrator->hydrate($data, RoleRepresentation::class);
    }
}
