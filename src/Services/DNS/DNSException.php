<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class DNSException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::DNS_CODE, $http_code, $previous);
	}
}
