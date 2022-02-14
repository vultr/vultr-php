<?php

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Services\VultrServiceException;

class BillingException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::BILL_CODE, $http_code, $previous);
	}
}
