<?php

namespace Vultr\VultrPhp\Services\Applications;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class ApplicationException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::APPLICATION_CODE, $http_code, $previous);
	}
}
