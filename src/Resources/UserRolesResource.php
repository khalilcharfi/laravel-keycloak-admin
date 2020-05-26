<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use App\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use App\Laravel\Keycloak\Admin\Representations\RoleRepresentation;
use GuzzleHttp\ClientInterface;

class UserRolesResource implements UserRolesResourceInterface
{
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;
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

    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        HydratorInterface $hydrator,
        ClientInterface $client,
        string $realm,
        string $id
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->client = $client;
        $this->realm = $realm;
        $this->id = $id;
        $this->hydrator = $hydrator;
    }

    public function all(): RepresentationCollectionInterface
    {
        $response = $this->client->get("/auth/admin/realms/{$this->realm}/users/{$this->id}/role-mappings");

        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveUserRoleMappingsException();
        }

        $json = (string)$response->getBody();
        $data = json_decode($json, true);

        $items = [];
        foreach (['client', 'realm'] as $scope) {
            $key = $scope . 'Mappings';
            if (!array_key_exists($key, $data)) {
                continue;
            }
            foreach ($data[$key] as $role) {
                $items[] = $this->hydrator->hydrate($role, RoleRepresentation::class);
            }
        }
        return new RepresentationCollection($items);
    }

    public function realm(): RealmLevelUserRolesResourceInterface
    {
        return $this->resourceFactory->createRealmLevelUserRolesResource($this->realm, $this->id);
    }

    public function client(string $id): ClientLevelUserRolesResourceInterface
    {
        return $this->resourceFactory->createClientLevelUserRolesResource($this->realm, $this->id, $id);
    }
}
