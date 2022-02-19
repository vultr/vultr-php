<?php

namespace Vultr\VultrPhp;

/**
 * This class will eventually have to be refactored when we get around to supporting an actual OAuth authentication mechanism.
 */
class VultrAuth
{
    public const AUTHORIZATION_HEADER = 'Authorization';

    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function getSecret() : string
    {
        return $this->secret;
    }

    public function getBearerToken() : string
    {
        return $this->getSecret();
    }

    public function getBearerTokenHead() : string
    {
        return 'Bearer '.$this->getBearerToken();
    }
}
