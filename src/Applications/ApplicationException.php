<?php

namespace Vultr\VultrPhp\Applications;

use Vultr\VultrPhp\VultrException;

class ApplicationException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::APPLICATION_CODE, $previous, $http_code);
	}
}
