<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ObjectStorage;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class ObjectStorageException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::OBJ_CODE, $http_code, $previous);
	}
}
