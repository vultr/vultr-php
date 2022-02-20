<?php

namespace Vultr\VultrPhp\Services\StartupScripts;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class StartupScriptException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::STARTUP_CODE, $http_code, $previous);
	}
}
