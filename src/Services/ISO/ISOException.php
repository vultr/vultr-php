<?php

namespace Vultr\VultrPhp\Services\ISO;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class ISOException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::ISO_CODE, $http_code, $previous);
	}
}
