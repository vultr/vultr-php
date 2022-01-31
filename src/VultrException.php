<?php

namespace Vultr\VultrPhp;

use Exception;
use Throwable;

class VultrException extends Exception
{
	public const DEFAULT_CODE = 300;
	public const ACCOUNT_CODE = 301;
	public const APPLICATION_CODE = 302;

	protected ?int $http_code = null;

	public function __construct(string $message, int $code = self::DEFAULT_CODE, ?Throwable $previous = null, ?int $http_code = null)
	{
		$this->http_code = $http_code;
		parent::__construct($message, $code, $previous);
	}

	public function getHTTPCode() : ?int
	{
		return $this->http_code;
	}
}
