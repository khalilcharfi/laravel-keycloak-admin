<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Token;

use DateTime;
use function date_create;

class Token
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $token;
    /**
     * @var DateTime
     */
    private $expires;

    public function __construct(string $type, string $token, DateTime $expires)
    {
        $this->type = ucfirst($type);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return (string)$this->getContent();
    }

    public function getContent()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->expires < date_create();
    }
}
