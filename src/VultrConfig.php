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
	 * These are guzzle options that users will ever need when using the lib.
	 * @see GuzzleHttp\RequestOptions
	 */
	public const ACCEPTABLE_OPTIONS = [
		RequestOptions::ALLOW_REDIRECTS,
		RequestOptions::CONNECT_TIMEOUT,
		RequestOptions::COOKIES,
		RequestOptions::DEBUG,
		RequestOptions::DELAY,
		RequestOptions::EXPECT,
		RequestOptions::FORCE_IP_RESOLVE,
		RequestOptions::HEADERS,
		RequestOptions::IDN_CONVERSION,
		RequestOptions::PROXY,
		RequestOptions::READ_TIMEOUT,
		RequestOptions::TIMEOUT,
		RequestOptions::QUERY,
		RequestOptions::VERIFY,
		RequestOptions::VERSION,
	];

	/**
	 * @param $guzzle_options - @see https://docs.guzzlephp.org/en/stable/request-options.html
	 */
	public static function generateGuzzleConfig(VultrAuth $auth, array $guzzle_options = []) : array
	{
		$defaults = [
			RequestOptions::TIMEOUT         => self::DEFAULT_TIMEOUT,
			RequestOptions::CONNECT_TIMEOUT => self::DEFAULT_TIMEOUT, // Do you even have internet?
			RequestOptions::HEADERS         => [],
		];

		$config = [];
		foreach ($guzzle_options as $option => $value)
		{
			if (!isset(self::ACCEPTABLE_OPTIONS[$option])) continue;

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

