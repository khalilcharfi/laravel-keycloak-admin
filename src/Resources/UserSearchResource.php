<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use App\Laravel\Keycloak\Admin\Exceptions\CannotRetrieveUsersException;
use App\Laravel\Keycloak\Admin\Hydrator\HydratorInterface;
use App\Laravel\Keycloak\Admin\Representations\RepresentationCollection;
use App\Laravel\Keycloak\Admin\Representations\UserRepresentation;
use GuzzleHttp\ClientInterface;
use RuntimeException;
use function http_build_query;
use function json_decode;

class UserSearchResource implements UserSearchResourceInterface
{
    use SearchableResource;

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
     * @var HydratorInterface
     */
    private $hydrator;


    public function __construct(
        ClientInterface $client,
        ResourceFactoryInterface $resourceFactory,
        HydratorInterface $hydrator,
        string $realm
    ) {
        $this->client = $client;
        $this->resourceFactory = $resourceFactory;
        $this->realm = $realm;
        $this->hydrator = $hydrator;
    }

    public function offset(int $offset = null): UserSearchResourceInterface
    {
        $this->withSearchOption('first', $offset);
        return $this;
    }

    public function limit(int $limit = null): UserSearchResourceInterface
    {
        $this->withSearchOption('max', $limit);
        return $this;
    }

    public function lastName(string $lastName): UserSearchResourceInterface
    {
        $this->withSearchOption('lastName', $lastName);
        return $this;
    }

    public function firstName(string $firstName): UserSearchResourceInterface
    {
        $this->withSearchOption('firstName', $firstName);
        return $this;
    }

    public function email(string $email): UserSearchResourceInterface
    {
        $this->withSearchOption('email', $email);
        return $this;
    }

    public function username(string $username): UserSearchResourceInterface
    {
        $this->withSearchOption('username', $username);
        return $this;
    }

    public function briefRepresentation(bool $briefRepresentation): UserSearchResourceInterface
    {
        $this->withSearchOption('briefRepresentation', $briefRepresentation);
        return $this;
    }

    public function query(string $query): UserSearchResourceInterface
    {
        $this->withSearchOption('search', $query);
        return $this;
    }

    public function __call($name, $arguments)
    {
        throw new RuntimeException("Unknown searchable method [$name]");
    }

    public function getIterator()
    {
        return $this->get();
    }

    public function get()
    {
        $options = $this->getSearchOptions();
        $queryString = '';
        if (!empty($options)) {
            $queryString = '?' . http_build_query($options);
        }

        $response = $this->client->get("/auth/admin/realms/{$this->realm}/users{$queryString}");
        if (200 !== $response->getStatusCode()) {
            throw new CannotRetrieveUsersException("Unable to retrieve users");
        }

        $json = (string)$response->getBody();
        $users = json_decode($json, true);

        $items = array_map(function ($user) {
            return $this->hydrator->hydrate($user, UserRepresentation::class);
        }, $users);

        return new RepresentationCollection($items);
    }

    public function first()
    {
        $result = $this->get();
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }
}
