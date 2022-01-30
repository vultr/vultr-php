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
	 * These are guzzle options no one will ever need and shouldn't be set.
	 */
	public const IGNORE_OPTIONS = [
		RequestOptions::SINK, // No, why would you need this?
		RequestOptions::MULTIPART, // Nothing in the Vultr API Supports Multipart requests.
		RequestOptions::FORM_PARAMS, // Ehh, its all sent as JSON.
		RequestOptions::BODY, // This doesn't need to be manipulated by users. Leave this to Guzzle.
		RequestOptions::AUTH, // We don't support HTTP Based Authentication. So no need to set.
		RequestOptions::JSON, // We handle the header's ourselves.
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
			if (isset(self::IGNORE_OPTIONS[$option])) continue;

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

