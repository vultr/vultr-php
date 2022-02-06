<?php

namespace Vultr\VultrPhp;

use Exception;
use Throwable;

class VultrException extends Exception
{
	public const DEFAULT_CODE = 200;
	public const SERVICE_CODE = 300;
	public const CLIENT_CODE = 400;

	protected ?int $http_code = null;

	public function __construct(string $message, int $code = self::DEFAULT_CODE, ?int $http_code = null, ?Throwable $previous = null)
	{
		$this->http_code = $http_code;
		parent::__construct($message, $code, $previous);
	}

	public function getHTTPCode() : ?int
	{
		return $this->http_code;
	}
}
