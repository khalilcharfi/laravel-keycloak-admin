<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Exceptions\CannotCreateRealmException;
use App\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Keycloak\Admin\Representations\RealmRepresentationInterface;
use GuzzleHttp\ClientInterface;

class RealmsResource implements RealmsResourceInterface
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
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ClientInterface $client,
        ResourceFactoryInterface $resourceFactory,
        HydratorInterface $hydrator
    ) {
        $this->client = $client;
        $this->resourceFactory = $resourceFactory;
        $this->hydrator = $hydrator;
    }

    public function create(?array $options = null): RealmCreateResourceInterface
    {
        $createResource = $this->resourceFactory->createRealmCreateResource();
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $createResource->$key($value);
            }
        }
        return $createResource;
    }

    public function realm($realm): RealmResourceInterface
    {
        return $this->resourceFactory->createRealmResource($realm);
    }

    public function add(RealmRepresentationInterface $realm): void
    {
        $data = $this->hydrator->extract($realm);

        $data['realm'] = $data['name'];
        unset($data['name'], $data['id'], $data['created']);

        $response = $this->client->post('/auth/admin/realms', ['body' => json_encode($data)]);

        if (201 !== $response->getStatusCode()) {
            throw new CannotCreateRealmException("Cannot create realm $data[realm]");
        }
    }

    public function all(): array
    {
        // TODO: Implement all() method.
    }
}
