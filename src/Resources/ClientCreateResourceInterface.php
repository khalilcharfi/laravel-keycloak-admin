<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

interface ClientCreateResourceInterface
{
    public function clientId(string $clientId): ClientCreateResourceInterface;

    public function save(): ClientResourceInterface;
}
