<?php

namespace Vultr\VultrPhp\Services\Instances;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class InstanceException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::INSTANCE_CODE, $http_code, $previous);
	}
}
