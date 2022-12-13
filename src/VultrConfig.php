<?php

declare(strict_types=1);

namespace Vultr\VultrPhp;

class VultrConfig
{
	public const VERSION = '1.0.2';
	public const API_VERSION = 'v2';
	public const API_URI = 'https://api.vultr.com/';
	public const USERAGENT = 'VultrPhp/'.self::VERSION. '(API_VERSION: '.self::API_VERSION.')';

	public const MANDATORY_HEADERS = [
		'Content-Type'                  => 'application/json',
		'Accept'                        => 'application/json',
		'User-Agent'                    => self::USERAGENT,
		VultrAuth::AUTHORIZATION_HEADER => '',
	];

	public static function generateHeaders(VultrAuth $auth) : array
	{
		$headers = [];
		foreach (self::MANDATORY_HEADERS as $option => $value)
		{
			$headers[$option] = $value;
		}
		$headers[VultrAuth::AUTHORIZATION_HEADER] = $auth->getBearerTokenHead();

		return $headers;
	}

	public static function getBaseURI() : string
	{
		return self::API_URI.self::API_VERSION.'/';
	}
}

