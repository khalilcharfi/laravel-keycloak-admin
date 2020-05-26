<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Resources;

use Kcharfi\Laravel\Keycloak\Admin\Representations\RealmRepresentationInterface;

interface RealmsResourceInterface
{
    /**
     * @param $realm
     * @return RealmResourceInterface
     */
    public function realm($realm): RealmResourceInterface;

    /**
     * @param RealmRepresentationInterface $realm
     */
    public function add(RealmRepresentationInterface $realm): void;

    /**
     * @param array|null $options
     * @return RealmCreateResourceInterface
     */
    public function create(?array $options = null): RealmCreateResourceInterface;

    /**
     * @return RealmRepresentationInterface[]
     */
    public function all(): array;
}
