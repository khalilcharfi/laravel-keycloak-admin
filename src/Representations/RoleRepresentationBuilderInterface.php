<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Representations;

interface RoleRepresentationBuilderInterface
{
    public function withName(string $name): self;

    public function withDescription(string $description): self;

    public function withClientRole(bool $isClientRole): self;

    public function withComposite(bool $composite): self;

    public function withContainerId(string $containerId): self;

    public function build(): RoleRepresentationInterface;
}
