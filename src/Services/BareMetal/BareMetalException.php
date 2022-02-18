<?php

namespace Vultr\VultrPhp\Services\BareMetal;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class BareMetalException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::BM_CODE, $http_code, $previous);
	}
}
