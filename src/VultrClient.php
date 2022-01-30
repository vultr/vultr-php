<?php

namespace Vultr\VultrPhp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class VultrClient
{
	private VultrAuth $auth;
	private Client $client;

	/**
	 * @param $auth
	 * @see VultrAuth
	 * @param $guzzle_options
	 * @see initGuzzleConfig
	 */
	public function __construct(VultrAuth $auth, array $guzzle_options = [])
	{
		$this->auth = $auth;
		$this->initGuzzleConfig($guzzle_options);
	}

	/**
	 * @param $guzzle_options - @see https://docs.guzzlephp.org/en/stable/request-options.html
	 */
	private function initGuzzleConfig(array $guzzle_options) : array
	{
		$defaults = [
			RequestOptions::HEADERS => [
				VultrAuth::AUTHORIZATION_HEADER => $this->auth->getBearerTokenHead()
			]
		];

		$config = [];
		foreach ($guzzle_options as $option => $value)
		{
			$config[$option] = $value;
		}

		foreach ($defaults as $option => $value)
		{
			if (isset($config[$option])) continue;

			$config[$option] = $value;
		}

		$this->client = new Client($config);
	}
}
