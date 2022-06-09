<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ObjectStorage;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

class ObjectStorageService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#operation/list-object-storage-clusters
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ObjectStorageException
	 * @return array
	 */
	public function getClusters(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('object-storage/clusters', new ObjStoreCluster(), $options);
	}

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

	/**
	 * @see https://www.vultr.com/api/#operation/create-object-storage
	 * @param $cluster_id - integer - @see https://www.vultr.com/api/#operation/list-object-storage-clusters
	 * @param $label - string|null - Null means omitted from the request.
	 * @throws ObjectStorageException
	 * @return ObjectStorage
	 */
	public function createObjectStoreSub(int $cluster_id, ?string $label = null) : ObjectStorage
	{
		$params = [
			'cluster_id' => $cluster_id
		];

		if ($label !== null)
		{
			$params['label'] = $label;
		}

		return $this->createObject('object-storage', new ObjectStorage(), $params);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/delete-object-storage
	 * @param $object_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws ObjectStorageException
	 * @return void
	 */
	public function deleteObjectStoreSub(string $object_id) : void
	{
		$this->deleteObject('object-storage/'.$object_id, new ObjectStorage());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/update-object-storage
	 * @param $obj - ObjectStorage - Fully initialized object with your updated parameters.
	 * @throws ObjectStorageException
	 * @return void
	 */
	public function updateObjectStoreSub(ObjectStorage $obj) : void
	{
		$this->patchObject('object-storage/'.$obj->getId(), $obj);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/regenerate-object-storage-keys
	 * @param $obj - ObjectStorage - Fully initialized object.
	 * @throws ObjectStorageException
	 * @throws VultrServiceException
	 * @throws VultrException
	 * @return ObjectStorage
	 */
	public function regenObjectStoreKeys(ObjectStorage $obj) : ObjectStorage
	{
		$client = $this->getClientHandler();

		try
		{
			$response = $client->post('object-storage/'.$obj->getId().'/regenerate-keys');
		}
		catch (VultrClientException $e)
		{
			throw new ObjectStorageException('Failed to regenerate object storage keys: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$return_obj = clone $obj;

		$decode = VultrUtil::decodeJSON((string)$response->getBody(), true);

		if (!isset($decode['s3_credentials']))
		{
			throw new VultrServiceException('Failed to deserialize response, invalid json. Missing `s3_credentials` during regeneration of s3 keys.');
		}

		$credentials = $decode['s3_credentials'];
		$return_obj->setS3Hostname($credentials['s3_hostname']);
		$return_obj->setS3AccessKey($credentials['s3_access_key']);
		$return_obj->setS3SecretKey($credentials['s3_secret_key']);

		return $return_obj;
	}
}
