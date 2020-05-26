<?php

namespace App\Keycloak\Admin\Representations;

use App\Keycloak\Admin\Hydrator\Hydrator;

class RealmRepresentationBuilder extends AbstractRepresentationBuilder implements RealmRepresentationBuilderInterface
{
    public function withName(string $name): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('realm', $name);
    }

    public function withEnabled(bool $enabled): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('enabled', $enabled);
    }

    public function withDisplayName(string $displayName): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('displayName', $displayName);
    }

    public function withAccessCodeLifespan(int $accessCodeLifespan): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accessCodeLifespan', $accessCodeLifespan);
    }

    public function withAccessCodeLifespanLogin(int $accessCodeLifespanLogin): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accessCodeLifespanLogin', $accessCodeLifespanLogin);
    }

    public function withAccessCodeLifespanUserAction(int $accessCodeLifespanUserAction)
        : RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accessCodeLifespanUserAction', $accessCodeLifespanUserAction);
    }

    public function withAccessTokenLifespan(int $accessTokenLifespan): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accessTokenLifespan', $accessTokenLifespan);
    }

    public function withAccessTokenLifespanForImplicitFlow(int $accessTokenLifespanForImplicitFlow
    ): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accessTokenLifespanForImplicitFlow', $accessTokenLifespanForImplicitFlow);
    }

    public function withAccountTheme(string $accountTheme): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('accountTheme', $accountTheme);
    }

    public function withActionTokenGeneratedByAdminLifespan(int $actionTokenGeneratedByAdminLifespan
    ): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('actionTokenGeneratedByAdminLifespan', $actionTokenGeneratedByAdminLifespan);
    }

    public function withActionTokenGeneratedByUserLifespan(int $actionTokenGeneratedByUserLifespan
    ): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('actionTokenGeneratedByUserLifespan', $actionTokenGeneratedByUserLifespan);
    }

    public function withAdminEventsDetailsEnabled(bool $adminEventsDetailsEnabled): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('adminEventsDetailsEnabled', $adminEventsDetailsEnabled);
    }

    public function withAdminEventsEnabled(bool $adminEventsEnabled): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('adminEventsEnabled', $adminEventsEnabled);
    }

    public function withAdminTheme(string $adminTheme): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('adminTheme', $adminTheme);
    }

    public function withAttributes(?array $attributes): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('attributes', $attributes);
    }

    public function withBruteForceProtected(bool $bruteForceProtected): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('bruteForceProtected', $bruteForceProtected);
    }

    public function withRememberMe(bool $rememberMe): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('rememberMe', $rememberMe);
    }

    public function withRoles($roles): RealmRepresentationBuilderInterface
    {
        return $this->setAttribute('roles', $roles);
    }

    public function build(): RealmRepresentationInterface
    {
        $data = $this->getAttributes();

        $data['enabled'] = $this->getAttribute('enabled', false);
        $data['name'] = $this->getAttribute('realm');

        $hydrator = new Hydrator();

        return $hydrator->hydrate($data, RealmRepresentation::class);
    }
}
