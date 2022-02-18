<?php

namespace Vultr\VultrPhp\Services\LoadBalancer;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class LoadBalancerException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::LB_CODE, $http_code, $previous);
	}
}
