<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class InstanceService extends VultrService
{
	public const FILTER_LABEL = 'label';
	public const FILTER_MAIN_IP = 'main_ip';
	public const FILTER_REGION = 'region';

	/**
	 * @see https://www.vultr.com/api/#operation/list-instances
	 * @param $filters - array|null - ENUM(FILTER_LABEL, FILTER_MAIN_IP, FILTER_REGION)
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws InstanceException
	 * @return Instance[]
	 */
	public function getInstances(?array $filters = null, ?ListOptions $options = null) : array
	{
		return $this->getListObjects('instances', new Instance(), $options, $filters);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance
	 * @param $id - string
	 * @throws InstanceException
	 * @return Instance
	 */
	public function getInstance(string $id) : Instance
	{
		return $this->getObject('instances/'.$id, new Instance());
	}
}
