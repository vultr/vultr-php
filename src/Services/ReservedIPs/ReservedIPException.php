<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ReservedIPs;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class ReservedIPException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::RESERVED_IP_CODE, $http_code, $previous);
	}
}
