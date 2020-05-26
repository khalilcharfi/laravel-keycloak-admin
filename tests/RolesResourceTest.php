<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Tests;

use Kcharfi\Laravel\Keycloak\Admin\Exceptions\CannotDeleteRoleException;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationBuilder;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationBuilderInterface;
use Kcharfi\Laravel\Keycloak\Admin\Representations\RoleRepresentationInterface;
use Kcharfi\Laravel\Keycloak\Admin\Resources\RolesResourceInterface;
use Kcharfi\Laravel\Keycloak\Admin\Tests\Traits\WithTemporaryRealm;

class RolesResourceTest extends TestCase
{
    use WithTemporaryRealm;

    /**
     * @var RolesResourceInterface
     */
    private $resource;
    /**
     * @var RoleRepresentationBuilderInterface
     */
    private $builder;

    public function setUp(): void
    {
        parent::setUp();
        $this->builder = new RoleRepresentationBuilder();
        $this->resource = $this->client->realm($this->temporaryRealm)->roles();
    }

    /**
     * @test
     */
    public function role_details_can_be_retrieved()
    {
        $role = $this
            ->client
            ->realm('master')
            ->roles()
            ->getByName('offline_access')
            ->toRepresentation();

        $this->assertInstanceOf(RoleRepresentationInterface::class, $role);
        $this->assertNotEmpty($role->getDescription());
    }

    /**
     * @test
     */
    public function roles_can_be_retrieved()
    {
        $roles = $this->resource->all();

        $this->assertGreaterThan(1, count($roles));

        /* @var RoleRepresentationInterface $adminRole */
        $adminRole = $roles->first(function (RoleRepresentationInterface $role) {
            return 'offline_access' == $role->getName();
        });

        $this->assertInstanceOf(RoleRepresentationInterface::class, $adminRole);
        $this->assertValidKeycloakId($adminRole->getId());
    }

    /**
     * @test
     */
    public function roles_can_be_created()
    {
        $roleName = $this->faker->slug;

        $id = $this->resource->add(
            $this->builder
                ->withName($roleName)
                ->build()
        )->getId();
        $this->assertTrue($this->roleExists($roleName));
        $this->assertValidKeycloakId($id);
    }

    private function roleExists($roleName)
    {
        $roles = $this->resource->all();

        $role = $roles->first(function (RoleRepresentationInterface $role) use ($roleName) {
            return $roleName == $role->getName();
        });

        return $role instanceof RoleRepresentationInterface;
    }

    /**
     * @test
     */
    public function an_exception_gets_thrown_when_the_role_cannot_be_deleted()
    {
        $roleName = $this->faker->slug;
        $this->expectException(CannotDeleteRoleException::class);
        $this->resource->deleteByName($roleName);
    }

    /**
     * @test
     */
    public function roles_can_be_deleted_by_name()
    {
        $roleName = $this->faker->slug;

        $id = $this->resource
            ->create()
            ->name($roleName)
            ->save()
            ->getId();

        $this->assertTrue($this->roleExists($roleName));

        $this->resource->delete($id);

        $this->assertFalse($this->roleExists($roleName));
    }

    /**
     * @test
     */
    public function roles_can_be_deleted_from_the_role_resource()
    {
        $roleName = $this->faker->slug;

        $this->resource
            ->create(['name' => $roleName])
            ->save();

        $this->resource
            ->getByName($roleName)
            ->delete();

        $this->assertFalse($this->roleExists($roleName));
    }

    /**
     * @test
     */
    public function roles_can_be_updated()
    {

        $roleName = $this->faker->slug;

        $oldDescription = $this->faker->text;
        $newDescription = $this->faker->text;

        $roleId = $this->resource
            ->create()
            ->name($roleName)
            ->description($oldDescription)
            ->save()
            ->getId();

        $this->resource
            ->get($roleId)
            ->update()
            ->description($newDescription)
            ->save();

        $role = $this->resource
            ->get($roleId)
            ->toRepresentation();

        $this->assertValidKeycloakId($roleId);
        $this->assertEquals($newDescription, $role->getDescription());
    }
}
