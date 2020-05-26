<?php

namespace App\Laravel\Keycloak\Admin\Representations;

interface RealmRepresentationBuilderInterface
{
    public function withName(string $name): self;

    public function withEnabled(bool $enabled): self;

    public function withDisplayName(string $displayName): self;

    public function withAccessCodeLifespan(int $accessCodeLifespan): self;

    public function withAccessCodeLifespanLogin(int $accessCodeLifespanLogin): self;

    public function withAccessCodeLifespanUserAction(int $accessCodeLifespanUserAction): self;

    public function withAccessTokenLifespan(int $accessTokenLifespan): self;

    public function withAccessTokenLifespanForImplicitFlow(int $accessTokenLifespanForImplicitFlow): self;

    public function withAccountTheme(string $accountTheme): self;

    public function withActionTokenGeneratedByAdminLifespan(int $actionTokenGeneratedByAdminLifespan): self;

    public function withActionTokenGeneratedByUserLifespan(int $actionTokenGeneratedByUserLifespan): self;

    public function withAdminEventsDetailsEnabled(bool $adminEventsDetailsEnabled): self;

    public function withAdminEventsEnabled(bool $adminEventsEnabled): self;

    public function withAdminTheme(string $adminTheme): self;

    public function withAttributes(?array $attributes): self;

    public function withBruteForceProtected(bool $bruteForceProtected): self;

    public function withRememberMe(bool $rememberMe): self;

    public function withRoles($roles): self;

    public function build(): RealmRepresentationInterface;
}
