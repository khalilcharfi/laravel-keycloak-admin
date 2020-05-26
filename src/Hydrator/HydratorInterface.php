<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Hydrator;

interface HydratorInterface
{
    /**
     * @param $object
     * @return array
     */
    public function extract($object): array;

    /**
     * @param $data
     * @param $class
     * @return mixed
     */
    public function hydrate(array $data, $class);
}
