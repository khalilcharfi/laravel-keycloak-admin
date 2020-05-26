<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

abstract class AbstractRepresentationBuilder
{
    protected $attributes = [];

    protected function getAttribute($key, $default = null)
    {
        return array_key_exists($key, $this->attributes) ? $this->attributes[$key] : $default;
    }

    protected function getAttributes()
    {
        return $this->attributes;
    }

    protected function setAttributes(array $attributes)
    {
        foreach ($attributes as $k => $v) {
            $this->setAttribute($k, $v);
        }
        return $this;
    }

    protected function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
}
