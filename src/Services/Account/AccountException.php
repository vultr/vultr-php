<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Account;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class AccountException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::ACCOUNT_CODE, $http_code, $previous);
	}
}
