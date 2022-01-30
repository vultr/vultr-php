<?php

namespace Vultr\VultrPhp;

use GuzzleHttp\RequestOptions;

class VultrConfig
{
	public const VERSION = '0.1';

	/**
	 * Default timeout in seconds.
	 */
	private const DEFAULT_TIMEOUT = 10;

	private const USERAGENT = 'VultrPhp/'.self::VERSION;

	public const MANDATORY_HEADERS = [
		'Content-Type'                  => 'application/json',
		'Accept'                        => 'application/json',
		'User-Agent'                    => self::USERAGENT,
		VultrAuth::AUTHORIZATION_HEADER => '',
	];

	/**
	 * @param $guzzle_options - @see https://docs.guzzlephp.org/en/stable/request-options.html
	 */
	public static function generateGuzzleConfig(VultrAuth $auth, array $guzzle_options = []) : array
	{
		$defaults = [
			RequestOptions::TIMEOUT         => self::DEFAULT_TIMEOUT,
			RequestOptions::CONNECT_TIMEOUT => self::DEFAULT_TIMEOUT, // Do you even have internet man?
			RequestOptions::HEADERS         => [],
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

		foreach (self::MANDATORY_HEADERS as $option => $value)
		{
			$config[RequestOptions::HEADERS][$option] = $value;
		}
		$config[RequestOptions::HEADERS][VultrAuth::AUTHORIZATION_HEADER] = $auth->getBearerTokenHead();

		return $config;
	}
}

