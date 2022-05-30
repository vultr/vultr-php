<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Regions;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class RegionException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::REGION_CODE, $http_code, $previous);
	}
}
