<?php

namespace Vultr\VultrPhp\Services\Backups;

use Exception;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
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

			$backups = $this->list('backups', new Backup(), $options, $params);
		}
		catch (VultrServiceException $e)
		{
			throw new BackupException('Failed to get backups: '.$e->getMessage(), $e->getHTTPCode());
		}

		return $backups;
	}

	public function getBackup(string $backup_id) : Backup
	{
		try
		{
			$response = $this->get('backups/'.$backup_id);
		}
		catch (VultrServiceException $e)
		{
			throw new BackupException('Failed to get backup: '.$e->getMessage(), $e->getHTTPCode());
		}

		try
		{
			$stdclass = json_decode($response->getBody());
			$backup = VultrUtil::mapObject($stdclass, new Backup(), 'backup');
		}
		catch (Exception $e)
		{
			throw new BackupException('Failed to deserialize backup object: '.$e->getMessage());
		}

		return $backup;
	}
}
