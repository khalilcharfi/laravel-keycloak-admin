<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use IteratorAggregate;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RepresentationCollectionInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\UserRepresentationInterface;

interface UserSearchResourceInterface extends IteratorAggregate
{
    public function offset(int $offset): self;

    public function limit(int $limit): self;

    public function lastName(string $lastName): self;

    public function firstName(string $firstName): self;

    public function email(string $email): self;

    public function username(string $username): self;

    public function briefRepresentation(bool $briefRepresentation): self;

    public function query(string $query): self;

    /**
     * @return RepresentationCollectionInterface
     */
    public function get();

    /**
     * @return UserRepresentationInterface|null
     */
    public function first();
}
