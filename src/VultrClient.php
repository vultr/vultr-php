<?php

namespace Vultr\VultrPhp;

use GuzzleHttp\Client;

class VultrClient
{
	private VultrAuth $auth;
	private Client $client;

	/**
	 * @param $auth
	 * @see VultrAuth
	 * @param $guzzle_options
	 * @see VultrConfig::generateGuzzleConfig
	 */
	public function __construct(VultrAuth $auth, array $guzzle_options = [])
	{
		$this->auth = $auth;
		$this->client = new Client(VultrConfig::generateGuzzleConfig($auth, $guzzle_options));
	}
}
