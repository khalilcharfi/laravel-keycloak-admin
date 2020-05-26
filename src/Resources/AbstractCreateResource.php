<?php

namespace App\Keycloak\Admin\Resources;

abstract class AbstractCreateResource
{
    protected $attributes;

    public function __construct()
    {
        $this->attributes = [];
    }

    public function withAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }
}
