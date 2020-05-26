<?php

namespace App\Laravel\Keycloak\Admin\Builders;

use Illuminate\Support\Str;
use function call_user_func_array;
use function method_exists;

abstract class AbstractBuilderAdapter
{
    private $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            return call_user_func_array([$this->builder, $name], $arguments);
        }
        $method = 'with' . Str::studly($name);

        if (method_exists($this->builder, $method)) {
            call_user_func_array([$this->builder, $method], $arguments);
            return $this;
        }
    }
}
