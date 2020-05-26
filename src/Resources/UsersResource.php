<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Exceptions\CannotCreateUserException;
use App\Keycloak\Admin\Exceptions\CannotDeleteUserException;
use App\Keycloak\Admin\Exceptions\CannotUpdateUserException;
use App\Keycloak\Admin\Exceptions\UnknownUserException;
use App\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Keycloak\Admin\Representations\UserRepresentationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class UsersResource implements UsersResourceInterface
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $realm;

    private $resourceFactory;

    private $hydrator;

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

    public function count()
    {
        $response = $this->client->post("/auth/admin/realms/{$this->realm}/users/count");
    }

    public function update(UserRepresentationInterface $user): void
    {
        $id = $user->getId();

        if (null == $id) {
            throw new CannotUpdateUserException("User id missing");
        }

        $data = $this->hydrator->extract($user);
        unset($data['created']);

        $response = $this->client->put("/auth/admin/realms/{$this->realm}/users/{$id}", ['body' => json_encode($data)]);

        if (204 !== $response->getStatusCode()) {
            throw new CannotUpdateUserException("User [$id] cannot be updated");
        }
    }

    public function add(UserRepresentationInterface $user): UserResourceInterface
    {
        $data = $this->hydrator->extract($user);
        unset($data['id'], $data['created']);

        $response = $this->client->post("/auth/admin/realms/{$this->realm}/users", ['body' => json_encode($data)]);

        if (201 !== $response->getStatusCode()) {
            $body = (string)$response->getBody();
            $data = json_decode($body, true);
            if (!empty($data['errorMessage'])) {
                throw new CannotCreateUserException($data['errorMessage']);
            }
            throw new CannotCreateUserException("Unable to create user");
        }

        $location = $response->getHeaderLine('Location');
        $parts = array_filter(explode('/', $location), 'strlen');
        $id = end($parts);
        return $this->get($id);
    }

    public function get($id): UserResourceInterface
    {
        return $this->resourceFactory->createUserResource($this->realm, $id);
    }

    /**
     * @param array|null $options
     * @return UserCreateResourceInterface
     */
    public function create(?array $options = null): UserCreateResourceInterface
    {
        $builderResource = $this->resourceFactory->createUserCreateResource($this->realm);
        if (null !== $options) {
            foreach ($options as $key => $value) {
                $builderResource->$key($value);
            }
        }
        return $builderResource;
    }

    public function deleteByEmail($email)
    {
        if (false == ($user = $this->getByEmail($email))) {
            throw new UnknownUserException("User with email [$email] does not exist");
        }
        return $this->deleteById($user->getId());
    }

    /**
     * @param $email
     * @return UserRepresentationInterface|null
     */
    public function getByEmail(string $email): ?UserResourceInterface
    {
        $user = $this->search()->email($email)->first();

        if ($user instanceof UserRepresentationInterface) {
            return $this->get($user->getId());
        }
        return null;
    }

    public function search(?array $options = null): UserSearchResourceInterface
    {
        $searchResource = $this->resourceFactory->createUserSearchResource($this->realm);

        if (null !== $options) {
            foreach ($options as $k => $v) {
                $searchResource->$k($v);
            }
        }

        return $searchResource;
    }

    public function deleteById($id)
    {
        $response = $this->client->delete("/auth/admin/realms/{$this->realm}/users/{$id}");

        if (204 != $response->getStatusCode()) {
            throw new CannotDeleteUserException("User with id [$id] cannot be deleted");
        }
    }

    public function deleteByUsername($username)
    {
        if (false == ($user = $this->getByUsername($username))) {
            throw new UnknownUserException("User with username [$username] does not exist");
        }
        return $this->deleteById($user->getId());
    }

    /**
     * @param $username
     * @return UserResourceInterface|null
     */
    public function getByUsername(string $username): ?UserResourceInterface
    {
        $user = $this->search()->username($username)->first();

        if ($user instanceof UserRepresentationInterface) {
            return $this->get($user->getId());
        }
        return null;
    }
}
