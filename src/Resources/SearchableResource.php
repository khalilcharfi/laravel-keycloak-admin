<?php

namespace App\Laravel\Keycloak\Admin\Resources;

use function array_key_exists;

trait SearchableResource
{
    protected $searchOptions = [];

    protected function withSearchOption($key, $value): void
    {
        $this->searchOptions[$key] = $value;
    }

    protected function getSearchOption($key, $default = null)
    {
        return array_key_exists($key, $this->searchOptions) ? $this->searchOptions[$key] : $default;
    }

    protected function getSearchOptions()
    {
        return $this->searchOptions;
    }
}
