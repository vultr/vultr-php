<?php

namespace Vultr\VultrPhp\Services\Snapshots;

use Throwable;
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
			throw new SnapshotException('Failed to get snapshots: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $snapshots;
	}

	public function getSnapshot(string $id) : Snapshot
	{
		try
		{
			$response = $this->get('snapshots/'.$id);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to get snapshot: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		try
		{
			$stdclass = json_decode($response->getBody());
			$snapshot = VultrUtil::mapObject($stdclass, new Snapshot(), 'snapshot');
		}
		catch (Throwable $e)
		{
			throw new SnapshotException('Failed to deserialize snapshot object: '.$e->getMessage(), null, $e);
		}

		return $snapshot;
	}
}
