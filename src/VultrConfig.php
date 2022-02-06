<?php

namespace Vultr\VultrPhp;

use GuzzleHttp\RequestOptions;

class VultrConfig
{
	public const VERSION = '0.1';

	public const API_VERSION = 'v2';

	public const API_URI = 'https://api.vultr.com/';

	/**
	 * Default timeout in seconds.
	 */
	private const DEFAULT_TIMEOUT = 10;

	private const USERAGENT = 'VultrPhp/'.self::VERSION. '(API_VERSION: '.self::API_VERSION.')';

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
		RequestOptions::ALLOW_REDIRECTS  => 0,
		RequestOptions::CONNECT_TIMEOUT  => 1,
		RequestOptions::COOKIES          => 2,
		RequestOptions::DEBUG            => 3,
		RequestOptions::DELAY            => 4,
		RequestOptions::EXPECT           => 5,
		RequestOptions::FORCE_IP_RESOLVE => 6,
		RequestOptions::HEADERS          => 7,
		RequestOptions::IDN_CONVERSION   => 8, // May be useful if we add support for multiple languages in API responses.
		RequestOptions::PROXY            => 9,
		RequestOptions::READ_TIMEOUT     => 10,
		RequestOptions::TIMEOUT          => 11,
		RequestOptions::QUERY            => 12,
		RequestOptions::VERIFY           => 13,
		RequestOptions::VERSION          => 14,
		'handler'                        => 15, // Can be used for unittests using Guzzles MockHandler
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
			if (!isset(self::ACCEPTABLE_OPTIONS[$option]))
			{
				throw new VultrClientException('Invalid acceptable guzzle option for VultrPhp - see VultrConfig::ACCEPTABLE_OPTIONS, OPTION: '.$option, null);
			}

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
		$config['base_uri'] = self::API_URI.self::API_VERSION.'/';

		return $config;
	}
}

