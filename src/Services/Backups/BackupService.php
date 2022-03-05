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
		$backups = [];
		if ($options === null)
		{
			$options = new ListOptions(100);
		}

		$params = [];
		if ($instance_id !== null)
		{
			$params['instance_id'] = $instance_id;
		}

		try
		{
			$backups = $this->list('backups', new Backup(), $options, $params);
		}
		catch (VultrServiceException $e)
		{
			throw new BackupException('Failed to get backups: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $backups;
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
