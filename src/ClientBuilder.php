<?php

namespace Kcharfi\Laravel\Keycloak\Admin;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Kcharfi\Laravel\Keycloak\Admin\Guzzle\DefaultHeadersMiddleware;
use Kcharfi\Laravel\Keycloak\Admin\Guzzle\TokenMiddleware;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\Hydrator;
use Kcharfi\Laravel\Keycloak\Admin\Resources\ResourceFactory;
use Kcharfi\Laravel\Keycloak\Admin\Token\TokenManager;

class ClientBuilder
{
    private $guzzle;

    private $realm;

    private $serverUrl;

    private $clientId;

    private $clientSecret;

    private $token;

    private $username;

    private $password;

    private $tokenManager;

    public function __construct()
    {
    }

    /**
     * @param ClientInterface $guzzle
     * @return ClientBuilder
     */
    public function withGuzzle(ClientInterface $guzzle): self
    {
        $this->guzzle = $guzzle;
        return $this;
    }

    /**
     * @param string $realm
     * @return ClientBuilder
     */
    public function withRealm(string $realm): self
    {
        $this->realm = $realm;
        return $this;
    }

    /**
     * @param string $url
     * @return ClientBuilder
     */
    public function withServerUrl(string $url): self
    {
        $this->serverUrl = $url;
        return $this;
    }

    /**
     * @param null|string $clientId
     * @return ClientBuilder
     */
    public function withClientId(?string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @param null|string $secret
     * @return ClientBuilder
     */
    public function withClientSecret(?string $secret): self
    {
        $this->clientSecret = $secret;
        return $this;
    }

    /**
     * @param string $username
     * @return ClientBuilder
     */
    public function withUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return ClientBuilder
     */
    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param null|string $token
     * @return ClientBuilder
     */
    public function withAuthToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function build()
    {
        $guzzle = $this->guzzle ?? $this->buildGuzzle();

        if (null == ($tokenManager = $this->tokenManager)) {
            $tokenManager = $this->buildTokenManager($guzzle);
        }

        $tokenMiddleware = new TokenMiddleware($tokenManager, $this->realm);

        $stack = HandlerStack::create();

        $stack->push($tokenMiddleware);
        $stack->push(new DefaultHeadersMiddleware());

        $client = new GuzzleClient(['http_errors' => false, 'handler' => $stack, 'base_uri' => $this->serverUrl]);

        $factory = new ResourceFactory($client, new Hydrator());

        return new Client($factory, $this->realm, $this->clientId);
    }

    /**
     * @return ClientInterface
     */
    private function buildGuzzle()
    {
        return new GuzzleClient(['base_uri' => $this->serverUrl]);
    }

    private function buildTokenManager(ClientInterface $guzzle)
    {
        return new TokenManager($this->username, $this->password, $this->clientId, $guzzle);
    }
}
