<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BlockStorage;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class BlockStorageService extends VultrService
{
	public function getBlockDevices(?ListOptions $options = null) : array
	{

	}

	public function getBlockDevice(string $block_id) : BlockStorage
	{

	}

	public function createBlockDevice(BlockStorage $block) : BlockStorage
	{

	}

	public function updateBlockDevice(BlockStorage $block) : void
	{

	}

	public function deleteBlockDevice(string $block_id) : void
	{

	}

	public function attachBlockDevice(string $block_id, string $instance_id, bool $live = true) : void
	{

	}

	public function detachBlockDevice(string $block_id, bool $live = true) : void
	{

	}
}
