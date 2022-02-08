<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrAuth;
use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\VultrConfig;
use Vultr\VultrPhp\VultrClientException;

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

	public function testGenerateGuzzleConfig()
	{
		$auth = new VultrAuth('Test1234');

		$acceptable = [];
		foreach (VultrConfig::ACCEPTABLE_OPTIONS as $option => $value)
		{
			// Ignore has a valid check later.
			if ($option === RequestOptions::HEADERS) continue;
			$acceptable[$option] = 0;
			if ($option === 'handler')
			{
				$acceptable[$option] = HandlerStack::create(new MockHandler());
			}
		}

		$config = VultrConfig::generateGuzzleConfig($auth, $acceptable);

		$this->assertArrayHasKey(RequestOptions::HEADERS, $config);
		foreach (VultrConfig::MANDATORY_HEADERS as $option => $value)
		{
			if ($option === VultrAuth::AUTHORIZATION_HEADER) continue;

			$this->assertArrayHasKey($option, $config[RequestOptions::HEADERS]);
			$this->assertEquals($value, $config[RequestOptions::HEADERS][$option]);
		}

		$this->assertArrayHasKey(VultrAuth::AUTHORIZATION_HEADER, $config[RequestOptions::HEADERS]);
		$this->assertEquals($auth->getBearerTokenHead(), $config[RequestOptions::HEADERS][VultrAuth::AUTHORIZATION_HEADER]);

		foreach (VultrConfig::ACCEPTABLE_OPTIONS as $option => $value)
		{
			$this->assertArrayHasKey($option, $config);
			if ($option === 'handler')
			{
				$this->assertIsCallable($config[$option]);
			}
		}
	}

	public function testGenerateGuzzleConfigException()
	{
		$ignore_test = [
			RequestOptions::FORM_PARAMS => 0, RequestOptions::HTTP_ERRORS => 0,
			RequestOptions::JSON => 0, RequestOptions::MULTIPART => 0
		];
		$auth = new VultrAuth('Test1234');

		$this->expectException(VultrClientException::class);
		$config = VultrConfig::generateGuzzleConfig($auth, $ignore_test);
	}

	public function testServiceHandle()
	{
		$client = VultrClient::create('Test1234');

		foreach (VultrClient::MAP as $prop => $class)
		{
			$this->assertInstanceOf($class, $client->$prop);
		}

		$this->assertNull($client->randomstuff);
	}
}
