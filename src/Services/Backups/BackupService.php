<?php

namespace Vultr\VultrPhp\Services\Backups;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class BackupService extends VultrService
{
	/**
	 * @param $instance_id - string|null - Get the current backups for the instance.
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BackupException
	 * @return Backup[]
	 */
	public function getBackups(?string $instance_id = null, ?ListOptions &$options = null) : array
	{
		$params = [];
		if ($instance_id !== null)
		{
			$params['instance_id'] = $instance_id;
		}

		return $this->getListObjects('backups', new Backup(), $options, $params);
	}

	/**
	 * @param $backup_id - string - UUID of the backup image.
	 * @throws BackupException
	 * @throws VultrException
	 * @return Backup
	 */
	public function getBackup(string $backup_id) : Backup
	{
		return $this->getObject('backups/'.$backup_id, new Backup());
	}
}
