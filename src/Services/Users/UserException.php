<?php

namespace Vultr\VultrPhp\Services\Users;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class UserException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::USER_CODE, $http_code, $previous);
	}
}
