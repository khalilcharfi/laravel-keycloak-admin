<?php
namespace App\Keycloak\Admin\Tests;

use App\Keycloak\Admin\Representations\ClientRepresentationInterface;
use App\Keycloak\Admin\Representations\RoleRepresentationInterface;
use App\Keycloak\Admin\Tests\Traits\WithTestClient;

class ClientsTest extends TestCase
{
    use WithTestClient;

    /**
     * @test
     */
    public function clients_can_be_retrieved()
    {
        $client = $this->client
            ->realm('master')
            ->clients()
            ->all()
            ->first(function (ClientRepresentationInterface $client) {
                return 'account' === $client->getClientId();
            });

        $this->assertInstanceOf(ClientRepresentationInterface::class, $client);
    }

    /**
     * @test
     */
     public function client_roles_can_be_retrieved() {

         // Find the id of the client "account"
         /* @var $client ClientRepresentationInterface */
         $client = $this->client
             ->realm('master')
             ->clients()
             ->all()
             ->filter(function(ClientRepresentationInterface $client) {
                 return $client->getClientId() == 'account';
             })
             ->first();

         $role = $this->client
             ->realm('master')
             ->client($client->getId())
             ->roles()
             ->all()
             ->first(function (RoleRepresentationInterface $role) {
                 return 'manage-account' === $role->getName();
             });

         $this->assertInstanceOf(RoleRepresentationInterface::class, $role);

     }
}
