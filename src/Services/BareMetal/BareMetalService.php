<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

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
}
