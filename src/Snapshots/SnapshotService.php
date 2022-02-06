<?php

namespace Vultr\VultrPhp\Snapshots;

use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\VultrService;
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

			$snapshots = $this->getClient()->list('snapshots', new Snapshot(), $options, $params);
		}
		catch (VultrClientException $e)
		{
			throw new SnapshotException('Failed to get snapshots: '.$e->getMessage());
		}

		return $snapshots;
	}

	public function getSnapshot(string $id) : Snapshot
	{

	}
}
