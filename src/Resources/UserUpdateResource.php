<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\UserRepresentationBuilderInterface;

class UserUpdateResource implements UserUpdateResourceInterface
{
    /**
     * @var UsersResourceInterface
     */
    private $usersResource;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var string
     */
    private $id;
    /**
     * @var UserRepresentationBuilderInterface
     */
    private $builder;

    public function __construct(
        UsersResourceInterface $usersResource,
        UserRepresentationBuilderInterface $builder,
        string $realm,
        string $id
    ) {
        $this->usersResource = $usersResource;
        $this->realm = $realm;
        $this->id = $id;
        $this->builder = $builder;
    }

    public function username(?string $username): UserUpdateResourceInterface
    {
        $this->builder->withUsername($username);
        return $this;
    }

    public function password(?string $password): UserUpdateResourceInterface
    {
        $this->builder->withPassword($password);
        return $this;
    }

    public function temporaryPassword(?string $password): UserUpdateResourceInterface
    {
        $this->builder->withTemporaryPassword($password);
        return $this;
    }

    public function enabled(?bool $enabled): UserUpdateResourceInterface
    {
        $this->builder->withEnabled($enabled);
        return $this;
    }

    public function email(?string $email): UserUpdateResourceInterface
    {
        $this->builder->withEmail($email);
        return $this;
    }

    public function save(): void
    {
        $this->builder->withId($this->id);
        $user = $this->builder->build();
        $this->usersResource->update($user);
    }
}
