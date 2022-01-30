<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrAuth;
use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\VultrConfig;
use GuzzleHttp\RequestOptions;
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

	public function testGenerateGuzzleConfig()
	{
		$auth = new VultrAuth('Test1234');
		$guzzle_options = VultrConfig::IGNORE_OPTIONS;
		$config = VultrConfig::generateGuzzleConfig($auth, $guzzle_options);

		$this->assertArrayHasKey(RequestOptions::HEADERS, $config);
		foreach (VultrConfig::MANDATORY_HEADERS as $option => $value)
		{
			if ($option === VultrAuth::AUTHORIZATION_HEADER) continue;

			$this->assertArrayHasKey($option, $config[RequestOptions::HEADERS]);
			$this->assertEquals($value, $config[RequestOptions::HEADERS][$option]);
		}

		$this->assertArrayHasKey(VultrAuth::AUTHORIZATION_HEADER, $config[RequestOptions::HEADERS]);
		$this->assertEquals($auth->getBearerTokenHead(), $config[RequestOptions::HEADERS][VultrAuth::AUTHORIZATION_HEADER]);

		foreach (VultrConfig::IGNORE_OPTIONS as $option => $value)
		{
			$this->assertArrayNotHasKey($option, $config);
		}
	}
}
