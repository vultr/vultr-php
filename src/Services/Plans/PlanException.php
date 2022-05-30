<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Plans;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class PlanException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::PLAN_CODE, $http_code, $previous);
	}
}
