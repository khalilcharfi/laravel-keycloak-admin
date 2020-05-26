<?php

namespace App\Laravel\Keycloak\Admin\Resources;

interface UserUpdateResourceInterface
{
    public function username(?string $username): self;

    public function password(?string $password): self;

    public function temporaryPassword(?string $password): self;

    public function enabled(?bool $enabled): self;

    public function email(?string $email): self;

    public function save(): void;
}
