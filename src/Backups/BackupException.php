<?php

namespace Vultr\VultrPhp\Backups;

use Vultr\VultrPhp\VultrException;

class BackupException extends VultrException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrException::BACKUP_CODE, $previous, $http_code);
	}
}
