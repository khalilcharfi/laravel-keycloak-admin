<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Token;

use DateInterval;
use GuzzleHttp\Client;
use Kcharfi\Laravel\Keycloak\Admin\Config;
use Kcharfi\Laravel\Keycloak\Admin\Enums\GrantType;
use RuntimeException;
use function date_create;
use function json_decode;

class TokenManager
{
    /**
     * @var Token[]
     */
    private $tokens;

    private $config;

    private $client;

    public function __construct(Config $config, Client $client)
    {
        $this->tokens = [];
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param $realm
     * @return Token
     */
    public function getToken($realm)
    {
        if (isset($this->tokens[$realm]) && $this->tokens[$realm]->isValid()) {
            return $this->tokens[$realm];
        }
        $formParams = [];

        switch ($this->config->getGrantType()) {
            case GrantType::PASSWORD:
                $formParams = [
                    'username' => $this->config->getUsername(),
                    'password' => $this->config->getPassword(),
                    'client_id' => $this->config->getClientId(),
                    'grant_type' => $this->config->getGrantType()
                ];
                break;
            case GrantType::CLIENT_CREDENTIALS:
                $formParams = [
                    'client_id' => $this->config->getClientId(),
                    'client_secret' => $this->config->getClientSecret(),
                    'grant_type' => $this->config->getGrantType()
                ];
                break;
        }

        $response = $this->client->post("/auth/realms/{$realm}/protocol/openid-connect/token", [
            'form_params' => $formParams
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException("Error getting token");
        }

        $data = json_decode((string)$response->getBody(), true);

        $expires = date_create()->add(new DateInterval(sprintf('PT%dS', $data['expires_in'])));

        $this->tokens[$realm] = new Token($data['token_type'], $data['access_token'], $expires);

        return $this->tokens[$realm];
    }
}
