<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BlockStorage;

use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class BlockStorageService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#tag/block/operation/list-blocks
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BlockStorageException
	 * @return array
	 */
	public function getBlockDevices(?ListOptions $options = null) : array
	{
		return $this->getListObjects('blocks', new BlockStorage(), $options);
	}

	/**
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
