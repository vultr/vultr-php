<?php

namespace Vultr\VultrPhp\Services\Snapshots;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class SnapshotService extends VultrService
{
	/**
	 * @param $description - string|null - Filter via description of the snapshots on the account.
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws SnapshotException
	 */
	public function getSnapshots(?string $description = null, ?ListOptions &$options = null) : array
	{
		$snapshots = [];

		if ($options === null)
		{
			$options = new ListOptions(100);
		}

		$params = [];
		if ($description !== null)
		{
			$params['description'] = $description;
		}

		try
		{
			$snapshots = $this->list('snapshots', new Snapshot(), $options, $params);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to get snapshots: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $snapshots;
	}

	/**
	 * @param $snapshot_id - string - UUID of the snapshot
	 * @throws SnapshotException
	 * @throws VultrException
	 * @return Snapshot
	 */
	public function getSnapshot(string $snapshot_id) : Snapshot
	{
		return $this->getObject('snapshots/'.$snapshot_id, new Snapshot());
	}

	/**
	 * @param $instance_id - string - UUID of the instance that will have the snapshot taken of.
	 * @param $description - string - What shall you name your snapshot?
	 * @throws SnapshotException
	 * @throws VultrException
	 * @return Snapshot
	 */
	public function createSnapshot(string $instance_id, string $description = '') : Snapshot
	{
		try
		{
			$response = $this->post('snapshots', [
				'instance_id' => $instance_id,
				'description' => $description
			]);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to create snapshot: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new Snapshot(), 'snapshot');
	}

	/**
	 * @param $url - string - Full URL of your raw snapshot. Ex https://www.vultr.com/your-amazing-disk-image.raw
	 * @param $description - string - What shall you name your snapshot?
	 * @throws SnapshotException
	 * @return Snapshot
	 */
	public function createSnapshotFromURL(string $url, string $description = '') : Snapshot
	{
		try
		{
			$response = $this->post('snapshots/create-from-url', [
				'url'         => $url,
				'description' => $description
			]);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to create snapshot: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new Snapshot(), 'snapshot');
	}

	/**
	 * @param $snapshot_id - string - UUID of snapshot.
	 * @throws SnapshotException
	 * @return void
	 */
	public function deleteSnapshot(string $snapshot_id) : void
	{
		try
		{
			$response = $this->delete('snapshots/'.$snapshot_id);
		}
		catch (VultrServiceException $e)
		{
			throw new SnapshotException('Failed to delete snapshot: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}
