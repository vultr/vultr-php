<?php

namespace Vultr\VultrPhp\Services\OperatingSystems;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class OperatingSystemException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::OS_CODE, $http_code, $previous);
	}
}
