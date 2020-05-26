<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Exceptions\CannotCreateRoleException;
use App\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveRoleException;
use App\Laravel\Keycloak\Admin\Exceptions\CannotUpdateRoleException;
use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentation;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use function json_encode;

class RolesResource implements RolesResourceInterface
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    public function __construct(
        ClientInterface $client,
        ResourceFactoryInterface $resourceFactory,
        HydratorInterface $hydrator,
        string $realm
    ) {
        $this->client = $client;
        $this->realm = $realm;
        $this->hydrator = $hydrator;
        $this->resourceFactory = $resourceFactory;
    }

    public function delete(string $id): void
    {
        $this->get($id)->delete();
    }

    public function get($id): RoleResourceInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/roles-by-id/{$id}");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveRoleException();
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);
        return $this->getByName($data['name']);
    }

    public function getByName($name): RoleResourceInterface
    {
        return $this->resourceFactory->createRoleResource($this->realm, $name);
    }

    public function deleteByName(string $name): void
    {
        $this->getByName($name)->delete();
    }

    /**
     * @return RepresentationCollectionInterface
     */
    public function all(): RepresentationCollectionInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/roles");

        $json = (string)$response->getBody();
        $users = json_decode($json, true);

        $items = array_map(function ($role) {
            return $this->hydrator->hydrate($role, RoleRepresentation::class);
        }, $users);

        return new RepresentationCollection($items);
    }

    public function add(RoleRepresentationInterface $role): RoleResourceInterface
    {
        $data = $this->hydrator->extract($role);
        unset($data['id']);

        $response = $this->client->post("/auth/admin/realms/{$this->realm}/roles", ['body' => json_encode($data)]);

        if (201 != $response->getStatusCode()) {
            throw new CannotCreateRoleException("Unable to create role");
        }

        $location = $response->getHeaderLine('Location');
        $parts = array_filter(explode('/', $location), 'strlen');
        $name = end($parts);
        return $this->getByName($name);
    }

    public function create(?array $options = null): RoleCreateResourceInterface
    {
        $resource = $this->resourceFactory->createRolesCreateResource($this->realm);
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $resource->$key($value);
            }
        }
        return $resource;
    }

    public function update(RoleRepresentationInterface $role): RoleResourceInterface
    {
        $data = $this->hydrator->extract($role);
        $roleName = $data['name'];

        $response = $this->client->put("/auth/admin/realms/{$this->realm}/roles/{$roleName}", [
            'body' => json_encode($data)
        ]);

        if (204 != $response->getStatusCode()) {
            throw new CannotUpdateRoleException("Unable to update role $roleName");
        }

        return $this->getByName($roleName);
    }
}
