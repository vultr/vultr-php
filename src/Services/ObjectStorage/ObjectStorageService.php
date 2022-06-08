<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ObjectStorage;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ObjectStorageService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#tag/s3/operation/list-object-storages
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ObjectStorageException
	 * @return array
	 */
	public function getObjectStoreSubs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('object-storage', new ObjectStorage(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/s3/operation/get-object-storage
	 * @param $object_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws ObjectStorageException
	 * @return ObjectStorage
	 */
	public function getObjectStoreSub(string $object_id) : ObjectStorage
	{
		return $this->getObject('object-storage/'.$object_id, new ObjectStorage());
	}
}
