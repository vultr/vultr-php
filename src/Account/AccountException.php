<?php

namespace Vultr\VultrPhp\Account;

use Vultr\VultrPhp\VultrException;

class AccountException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::ACCOUNT_CODE, $previous, $http_code);
	}
}
