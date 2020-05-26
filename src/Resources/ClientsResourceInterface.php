<?php

namespace App\Keycloak\Admin\Resources;

use App\Keycloak\Admin\Representations\ClientRepresentationInterface;
use App\Keycloak\Admin\Representations\RepresentationCollectionInterface;

interface ClientsResourceInterface
{
    /**
     * @param string $id
     * @return ClientResourceInterface
     */
    public function get(string $id): ClientResourceInterface;

    public function add(ClientRepresentationInterface $client): ClientResourceInterface;

    public function create(): ClientCreateResourceInterface;

    /**
     * @return RepresentationCollectionInterface
     */
    public function all(): RepresentationCollectionInterface;
}
