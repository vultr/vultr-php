<?php

namespace Vultr\VultrPhp\Services\VPC;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class VPCException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::VPC_CODE, $http_code, $previous);
	}
}
