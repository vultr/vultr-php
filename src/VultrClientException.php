<?php

namespace Vultr\VultrPhp;

use Vultr\VultrPhp\VultrException;

class VultrClientException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::CLIENT_CODE, $previous, $http_code);
	}
}
