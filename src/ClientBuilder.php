<?php

namespace Kcharfi\Laravel\Keycloak\Admin;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Kcharfi\Laravel\Keycloak\Admin\Enums\GrantType;
use Kcharfi\Laravel\Keycloak\Admin\Guzzle\DefaultHeadersMiddleware;
use Kcharfi\Laravel\Keycloak\Admin\Guzzle\TokenMiddleware;
use Kcharfi\Laravel\Keycloak\Admin\Hydrator\Hydrator;
use Kcharfi\Laravel\Keycloak\Admin\Resources\ResourceFactory;
use Kcharfi\Laravel\Keycloak\Admin\Token\TokenManager;

class ClientBuilder
{
    private $guzzle;

    private $config;

    private $token;

    private $tokenManager;

    public function __construct()
    {
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }


    /**
     * @param Config $config
     * @return ClientBuilder
     */
    public function withConfig(Config $config): self
    {
        $this->config = $config;
        return $this;
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
        $this->getConfig()->setClientId($clientId);
        return $this;
    }

    /**
     * @param null|string $secret
     * @return ClientBuilder
     */
    public function withClientSecret(?string $secret): self
    {
        $this->getConfig()->setClientSecret($secret);
        return $this;
    }

    /**
     * @param null|string $grantType
     * @return ClientBuilder
     */
    public function withGrantType(?string $grantType = GrantType::PASSWORD): self
    {
        $this->getConfig()->setGrantType($grantType);
        return $this;
    }

    /**
     * @param string $username
     * @return ClientBuilder
     */
    public function withUsername(string $username): self
    {
        $this->getConfig()->setUsername($username);
        return $this;
    }

    /**
     * @param string $password
     * @return ClientBuilder
     */
    public function withPassword(string $password): self
    {
        $this->getConfig()->setPassword($password);
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

        $tokenMiddleware = new TokenMiddleware($tokenManager, $this->getConfig()->getRealm());

        $stack = HandlerStack::create();

        $stack->push($tokenMiddleware);
        $stack->push(new DefaultHeadersMiddleware());

        $client = new GuzzleClient(['http_errors' => false, 'handler' => $stack,
            'base_uri' => $this->getConfig()->getUrl()]);

        $factory = new ResourceFactory($client, new Hydrator());

        return new Client($factory, $this->getConfig()->getRealm(), $this->getConfig()->getClientId());
    }

    /**
     * @return ClientInterface
     */
    private function buildGuzzle()
    {
        return new GuzzleClient(['base_uri' => $this->getConfig()->getUrl()]);
    }

    private function buildTokenManager(ClientInterface $guzzle)
    {
        return new TokenManager($this->getConfig(), $guzzle);
    }
}
