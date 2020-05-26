<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Exception;
use GuzzleHttp\ClientInterface;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotAssignRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveUserRoleMappingsException;
use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotUnlinkRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentation;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;

class ClientLevelUserRolesResource implements ClientLevelUserRolesResourceInterface
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
    private $userId;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(
        ClientInterface $client,
        HydratorInterface $hydrator,
        string $realm,
        string $userId,
        string $clientId
    ) {
        $this->client = $client;
        $this->realm = $realm;
        $this->userId = $userId;
        $this->clientId = $clientId;
        $this->hydrator = $hydrator;
    }

    public function all(): RepresentationCollectionInterface
    {
        $url = "/auth/admin/realms/{$this->realm}/users/{$this->userId}/role-mappings/clients/{$this->clientId}";
        $response = $this->client->get($url);
        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveUserRoleMappingsException();
        }
        $json = (string)$response->getBody();
        $data = json_decode($json, true);
        $items = array_map(function ($role) {
            return $this->hydrator->hydrate($role, RoleRepresentation::class);
        }, $data);
        return new RepresentationCollection($items);
    }

    public function available(): RepresentationCollectionInterface
    {
        throw new Exception("TODO");
    }

    public function effective(): RepresentationCollectionInterface
    {
        throw new Exception("TODO");
    }

    public function add(RoleRepresentationInterface $role)
    {
        $data = $this->hydrator->extract($role);
        $url = "/auth/admin/realms/{$this->realm}/users/{$this->userId}/role-mappings/clients/{$this->clientId}";

        $response = $this->client->post($url, ['body' => json_encode([$data])]);

        if (204 !== $response->getStatusCode()) {
            throw new CannotAssignRoleException();
        }
    }

    public function delete(RoleRepresentationInterface $role)
    {
        $data = $this->hydrator->extract($role);
        $url = "/auth/admin/realms/{$this->realm}/users/{$this->userId}/role-mappings/clients/{$this->clientId}";

        $response = $this->client->delete($url, ['body' => json_encode([$data])]);

        if (204 !== $response->getStatusCode()) {
            throw new CannotUnlinkRoleException();
        }
    }
}
