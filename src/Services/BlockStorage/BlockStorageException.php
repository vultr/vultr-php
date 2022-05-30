<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BlockStorage;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class BlockStorageException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::BLOCK_CODE, $http_code, $previous);
	}
}
