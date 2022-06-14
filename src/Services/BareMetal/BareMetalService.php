<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

class BareMetalService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#operation/list-baremetals
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BareMetalException
	 * @return BareMetal[]
	 */
	public function getBareMetals(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('bare-metals', new BareMetal(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function getBareMetal(string $id) : BareMetal
	{
		return $this->getObject('bare-metals/'.$id, new BareMetal());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/delete-baremetal
	 * @throws BareMetalException
	 * @return void
	 */
	public function deleteBareMetal(string $id) : void
	{
		$this->deleteObject('bare-metals/'.$id, new BareMetal());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/create-baremetal
	 * @param $payload - BareMetalCreate
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function createBareMetal(BareMetalCreate $payload) : BareMetal
	{
		return $this->createObject('bare-metals', new BareMetal(), $payload->getPayloadParams());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/update-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $payload - BareMetalUpdate
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function updateBareMetal(string $id, BareMetalUpdate $payload) : BareMetal
	{
		$client = $this->getClientHandler();

		try
		{
			$response = $client->patch('bare-metals/'.$id, $payload->getPayloadParams());
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to update baremetal server: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new BareMetal();

		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}
}
