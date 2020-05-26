<?php

namespace App\Keycloak\Admin;

use App\Keycloak\Admin\Exceptions\DefaultClientIdMissingException;
use App\Keycloak\Admin\Exceptions\DefaultRealmMissingException;
use App\Keycloak\Admin\Resources\ClientResourceInterface;
use App\Keycloak\Admin\Resources\ClientsResourceInterface;
use App\Keycloak\Admin\Resources\ResourceFactoryInterface;
use App\Keycloak\Admin\Resources\RolesResourceInterface;
use App\Keycloak\Admin\Resources\UserResourceInterface;
use App\Keycloak\Admin\Resources\UsersResourceInterface;

class Client
{
    /**
     * @var string|null
     */
    private $defaultRealm;
    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;
    /**
     * @var string|null
     */
    private $defaultClientId;

    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        ?string $defaultRealm = null,
        ?string $defaultClientId = null
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->defaultRealm = $defaultRealm;
        $this->defaultClientId = $defaultClientId;
    }

    public function users(): UsersResourceInterface
    {
        return $this->resourceFactory->createUsersResource($this->checkDefaultRealm());
    }

    public function user(string $id): UserResourceInterface
    {
        return $this->resourceFactory->createUserResource($this->checkDefaultRealm(), $id);
    }

    private function checkDefaultRealm()
    {
        if (null === $this->defaultRealm) {
            throw new DefaultRealmMissingException("The default realm is not set");
        }
        return $this->defaultRealm;
    }

    private function checkDefaultClientId()
    {
        if (null === $this->defaultClientId) {
            throw new DefaultClientIdMissingException("The default client id is not set");
        }
        return $this->defaultClientId;
    }

    public function roles(): RolesResourceInterface
    {
        return $this->resourceFactory->createRolesResource($this->checkDefaultRealm());
    }

    public function clients(): ClientsResourceInterface
    {
        return $this->resourceFactory->createClientsResource($this->checkDefaultRealm());
    }

    public function client(?string $id = null): ClientResourceInterface
    {
        if (null === $id) {
            $id = $this->checkDefaultClientId();
        }
        return $this->resourceFactory->createClientResource($this->checkDefaultRealm(), $id);
    }

    public function realms()
    {
        return $this->resourceFactory->createRealmsResource();
    }

    public function realm(?string $realm = null)
    {
        if (null === $realm) {
            $realm = $this->checkDefaultRealm();
        }
        return $this->resourceFactory->createRealmResource($realm);
    }
}
