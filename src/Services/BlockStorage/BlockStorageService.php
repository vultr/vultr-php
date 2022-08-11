<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BlockStorage;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\VultrClientException;

class BlockStorageService extends VultrService
{
	/**
	 * Retrieve block storage devices on the account.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/list-blocks
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BlockStorageException
	 * @return array
	 */
	public function getBlockDevices(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('blocks', new BlockStorage(), $options);
	}

	/**
	 * Get a specific block storage device on the account.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/get-block
	 * @param $block_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BlockStorageException
	 * @return BlockStorage
	 */
	public function getBlockDevice(string $block_id) : BlockStorage
	{
		return $this->getObject('blocks/'.$block_id, new BlockStorage());
	}

	/**
	 * Create a new block storage device in a region. The size range differs based on the block_type
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/create-block
	 * @param $block - BlockStorage
	 * @throws BlockStorageException
	 * @return array
	 */
	public function createBlockDevice(BlockStorage $block) : BlockStorage
	{
		return $this->createObject('blocks', new BlockStorage(), $block->getInitializedProps());
	}

	/**
	 * Update information on the block storage device.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/update-block
	 * @param $block - BlockStorage
	 * @throws BlockStorageException
	 * @return void
	 */
	public function updateBlockDevice(BlockStorage $block) : void
	{
		$this->patchObject('blocks/'.$block->getId(), $block);
	}

	/**
	 * Delete the block storage device.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/delete-block
	 * @param $block_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BlockStorageException
	 * @return void
	 */
	public function deleteBlockDevice(string $block_id) : void
	{
		$this->deleteObject('blocks/'.$block_id, new BlockStorage());
	}

	/**
	 * Attach the block storage device to a virtual machine.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/attach-block
	 * @param $block_id - string - Example cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $live - bool
	 * @throws BlockStorageException
	 * @return void
	 */
	public function attachBlockDevice(string $block_id, string $instance_id, bool $live = true) : void
	{
		try
		{
			$this->getClientHandler()->post('blocks/'.$block_id.'/attach', [
				'instance_id' => $instance_id,
				'live' => $live
			]);
		}
		catch (VultrClientException $e)
		{
			throw new BlockStorageException('Failed to attach block device: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * Detach the block storage device from the virtual machine.
	 *
	 * @see https://www.vultr.com/api/#tag/block/operation/detach-block
	 * @param $block_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $live - bool
	 * @throws BlockStorageException
	 * @return void
	 */
	public function detachBlockDevice(string $block_id, bool $live = true) : void
	{
		try
		{
			$this->getClientHandler()->post('blocks/'.$block_id.'/detach', ['live' => $live]);
		}
		catch (VultrClientException $e)
		{
			throw new BlockStorageException('Failed to detach block device: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}
