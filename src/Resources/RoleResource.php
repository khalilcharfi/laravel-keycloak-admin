<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use GuzzleHttp\ClientInterface;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotDeleteRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveRoleRepresentationException;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentation;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;
use function json_decode;

class RoleResource implements RoleResourceInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
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
    private $name;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ClientInterface $client,
        ResourceFactoryInterface $resourceFactory,
        HydratorInterface $hydrator,
        string $realm,
        string $name
    ) {
        $this->client = $client;
        $this->resourceFactory = $resourceFactory;
        $this->realm = $realm;
        $this->name = $name;
        $this->hydrator = $hydrator;
    }

    public function getRealm(): string
    {
        return $this->realm;
    }

    public function getId(): string
    {
        return $this->toRepresentation()->getId();
    }

    public function toRepresentation(): RoleRepresentationInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/roles/{$this->name}");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveRoleRepresentationException("Unable to retrieve [$this->name] role details");
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);

        return $this->hydrator->hydrate($data, RoleRepresentation::class);
    }

    public function getName(): string
    {
        return $this->getName();
    }

    public function delete(): void
    {
        $response = $this->client->delete("/auth/admin/realms/{$this->realm}/roles/{$this->name}");

        if (204 !== $response->getStatusCode()) {
            throw new CannotDeleteRoleException("Role {$this->name} of realm {$this->realm} cannot be deleted");
        }
    }

    public function update(?array $options = []): RoleUpdateResourceInterface
    {
        $updateResource = $this->resourceFactory->createRoleUpdateResource($this->realm, $this->name);
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $updateResource->$key($value);
            }
        }
        return $updateResource;
    }
}
