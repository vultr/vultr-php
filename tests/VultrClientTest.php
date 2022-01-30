<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrAuth;
use Vultr\VultrPhp\VultrClient;
use PHPUnit\Framework\TestCase;

class VultrClientTest extends TestCase
{
	public function testAuth()
	{
		$auth = new VultrAuth('Test1234');
		$this->assertEquals('Test1234', $auth->getSecret());
		$this->assertEquals('Test1234', $auth->getBearerToken());
		$this->assertEquals('Bearer '.$auth->getBearerToken(), $auth->getBearerTokenHead());
	}
}
