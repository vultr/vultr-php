<?php

namespace Vultr\VultrPhp\Backups;

use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class BackupService extends VultrService
{
	public function getBackups(?string $instance_id = null, ?ListOptions &$options = null) : array
	{
		$backups = [];
		try
		{
			if ($options === null)
			{
				$options = new ListOptions(100);
			}

			$params = [];
			if ($instance_id !== null)
			{
				$params['instance_id'] = $instance_id;
			}

			$backups = $this->getClient()->list('backups', new Backup(), $options, $params);
		}
		catch (VultrClientException $e)
		{
			throw new BackupException('Failed to get backups: '.$e->getMessage());
		}

		return $backups;
	}

	public function getBackup(string $backup_id) : Backup
	{

	}
}
