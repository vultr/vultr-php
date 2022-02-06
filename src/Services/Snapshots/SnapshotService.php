<?php

namespace Vultr\VultrPhp\Services\Snapshots;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class SnapshotService extends VultrService
{
	public function getSnapshots(?string $description = null, ?ListOptions &$options = null) : array
	{
		$snapshots = [];

		try
		{
			if ($options === null)
			{
				$options = new ListOptions(100);
			}

			$params = [];
			if ($description !== null)
			{
				$params['description'] = $description;
			}

			$snapshots = $this->list('snapshots', new Snapshot(), $options, $params);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to get snapshots: '.$e->getMessage());
		}

		return $snapshots;
	}

	public function getSnapshot(string $id) : Snapshot
	{

	}
}
