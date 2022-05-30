<?php

declare(strict_types=1);

namespace Vultr\VultrPhp;

use Throwable;
use Vultr\VultrPhp\VultrException;

class VultrClientException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::CLIENT_CODE, $http_code, $previous);
	}
}
