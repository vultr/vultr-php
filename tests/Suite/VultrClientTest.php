<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrAuth;
use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\VultrConfig;
use Vultr\VultrPhp\VultrClientException;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;

use Vultr\VultrPhp\Tests\VultrTest;

class VultrClientTest extends VultrTest
{
	public function testAuth()
	{
		$auth = new VultrAuth('Test1234');
		$this->assertEquals('Test1234', $auth->getSecret());
		$this->assertEquals('Test1234', $auth->getBearerToken());
		$this->assertEquals('Bearer '.$auth->getBearerToken(), $auth->getBearerTokenHead());
	}

	public function testClientCreate()
	{
		$client = VultrClient::create(new Client(), 'Test1234');

		$this->assertInstanceOf(VultrClient::class, $client);
	}

	public function testServiceHandle()
	{
		$client = VultrClient::create(new Client(), 'Test1234');

		foreach (VultrClient::MAP as $prop => $class)
		{
			$this->assertInstanceOf($class, $client->$prop);
		}

		$this->assertNull($client->randomstuff);
	}
}
