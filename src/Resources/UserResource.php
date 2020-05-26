<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Exceptions\CannotDeleteUserException;
use App\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveUserException;
use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\UserRepresentation;
use App\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;
use GuzzleHttp\ClientInterface;

class UserResource implements UserResourceInterface
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
        ClientInterface $client,
        ResourceFactoryInterface $resourceFactory,
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

    public function toRepresentation(): UserRepresentationInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/users/{$this->id}");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveUserException("Cannot retrieve user details of user {$this->id}");
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);
        return $this->hydrator->hydrate($data, UserRepresentation::class);
    }

    public function getRealm(): string
    {
        return $this->realm;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function roles(): UserRolesResourceInterface
    {
        return $this->resourceFactory->createUserRolesResource($this->realm, $this->id);
    }

    public function update(?array $options = null): UserUpdateResourceInterface
    {
        $resource = $this->resourceFactory->createUserUpdateResource($this->realm, $this->id);
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $resource->$key($value);
            }
        }
        return $resource;
    }

    public function delete()
    {
        $response = $this->client->delete("/auth/admin/realms/{$this->realm}/users/{$this->id}");

        if (204 !== $response->getStatusCode()) {
            throw new CannotDeleteUserException();
        }
    }
}
