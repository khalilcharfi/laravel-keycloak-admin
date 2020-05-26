<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use GuzzleHttp\ClientInterface;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotCreateRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveRolesException;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentation;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

class ClientRolesResource implements ClientRolesResourceInterface
{
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var string
     */
    private $id;
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        ClientInterface $client,
        HydratorInterface $hydrator,
        string $realm,
        string $id
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->realm = $realm;
        $this->id = $id;
        $this->client = $client;
        $this->hydrator = $hydrator;
    }

    public function all(): RepresentationCollectionInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/clients/{$this->id}/roles");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveRolesException();
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);

        $items = [];
        foreach ($data as $role) {
            $items[] = $this->hydrator->hydrate($role, RoleRepresentation::class);
        }

        return new RepresentationCollection($items);
    }

    public function create(?array $options = null): ClientRoleCreateResourceInterface
    {
        $resource = $this->resourceFactory->createClientRoleCreateResource($this->realm, $this->id);
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $resource->{$key}($value);
            }
        }
        return $resource;
    }

    public function add(RoleRepresentationInterface $role): ClientRoleResourceInterface
    {
        if (!$role->isClientRole()) {
            throw new \InvalidArgumentException("The role is not a client role");
        }

        $data = $this->hydrator->extract($role);

        $url = "/auth/admin/realms/{$this->realm}/clients/{$this->id}/roles";
        $response = $this->client->post($url, ['body' => json_encode($data)]);

        if (201 !== $response->getStatusCode()) {
            throw new CannotCreateRoleException();
        }

        $location = $response->getHeaderLine('Location');
        $parts = array_filter(explode('/', $location), 'strlen');
        $name = end($parts);
        return $this->getByName($name);
    }

    public function getByName($name): ClientRoleResourceInterface
    {
        return $this->resourceFactory->createClientRoleResource($this->realm, $this->id, $name);
    }
}
