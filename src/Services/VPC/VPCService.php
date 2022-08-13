<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\VPC;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

/**
 * Virtual private cloud service handler, for all vpcs endpoints.
 *
 * @see https://www.vultr.com/api/#tag/VPCs
 */
class VPCService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#tag/VPCs/operation/get-vpc
	 * @param $vpc_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws VPCException
	 * @return VirtualPrivateCloud
	 */
	public function getVPC(string $vpc_id) : VirtualPrivateCloud
	{
		return $this->getObject('vpcs/'.$vpc_id, new VirtualPrivateCloud());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/VPCs/operation/list-vpcs
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws VPCException
	 * @return array
	 */
	public function getVPCs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('vpcs', new VirtualPrivateCloud(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/VPCs/operation/create-vpc
	 * @param $vpc - VirtualPrivateCloud
	 * @throws VPCException
	 * @return VirtualPrivateCloud
	 */
	public function createVPC(VirtualPrivateCloud $vpc) : VirtualPrivateCloud
	{
		return $this->createObject('vpcs', new VirtualPrivateCloud(), $vpc->getInitializedProps());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/VPCs/operation/update-vpc
	 * @param $vpc - VirtualPrivateCloud
	 * @throws VPCException
	 * @return void
	 */
	public function updateVPC(VirtualPrivateCloud $vpc) : void
	{
		$this->patchObject('vpcs/'.$vpc->getId(), $vpc);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/VPCs/operation/delete-vpc
	 * @param $vpc_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws VPCException
	 * @return void
	 */
	public function deleteVPC(string $vpc_id) : void
	{
		$this->deleteObject('vpcs/'.$vpc_id, new VirtualPrivateCloud());
	}
}

