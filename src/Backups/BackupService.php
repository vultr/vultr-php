<?php

namespace Vultr\VultrPhp\Backups;

use Vultr\VultrPhp\VultrException;
use Vultr\VultrPhp\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class BackupService extends VultrService
{
	public function getBackups(string $instance_id, ?ListOptions &$options = null) : array
	{

	}

	public function getBackup(string $backup_id) : Backup
	{

	}
}
