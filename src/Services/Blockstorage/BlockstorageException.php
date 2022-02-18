<?php

namespace Vultr\VultrPhp\Services\Blockstorage;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class BlockstorageException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::BLOCK_CODE, $http_code, $previous);
	}
}
