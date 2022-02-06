<?php

namespace Vultr\VultrPhp\Snapshots;

use Vultr\VultrPhp\VultrException;

class SnapshotException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::SNAPSHOT_CODE, $previous, $http_code);
	}
}
