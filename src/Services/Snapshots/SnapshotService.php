<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Snapshots;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

/**
 * Snapshot service handler, for all snapshots endpoints.
 *
 * @see https://www.vultr.com/api/#tag/snapshot
 */
class SnapshotService extends VultrService
{
	/**
	 * @param $description - string|null - Filter via description of the snapshots on the account.
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws SnapshotException
	 * @return Snapshot[]
	 */
	public function getSnapshots(?string $description = null, ?ListOptions &$options = null) : array
	{
		$params = [];
		if ($description !== null)
		{
			$params['description'] = $description;
		}

		return $this->getListObjects('snapshots', new Snapshot(), $options, $params);
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
		return $this->createObject('snapshots', new Snapshot(), [
			'instance_id' => $instance_id,
			'description' => $description
		]);
	}

	/**
	 * @param $url - string - Full URL of your raw snapshot. Ex https://www.vultr.com/your-amazing-disk-image.raw
	 * @param $description - string - What shall you name your snapshot?
	 * @throws SnapshotException
	 * @return Snapshot
	 */
	public function createSnapshotFromURL(string $url, string $description = '') : Snapshot
	{
		return $this->createObject('snapshots/create-from-url', new Snapshot(), [
			'url'         => $url,
			'description' => $description
		]);
	}

	/**
	 * @param $snapshot_id - string - UUID of snapshot.
	 * @throws SnapshotException
	 * @return void
	 */
	public function deleteSnapshot(string $snapshot_id) : void
	{
		$this->deleteObject('snapshots/'.$snapshot_id, new Snapshot());
	}
}
