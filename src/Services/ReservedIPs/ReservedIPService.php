<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ReservedIPs;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ReservedIPService extends VultrService
{
	/**
	 * @param $reserved_id - string - UUID of the reserved ip
	 * @throws ReservedIPException
	 * @throws VultrException
	 * @return ReservedIP
	 */
	public function getReservedIP(string $reserved_id) : ReservedIP
	{
		return $this->getObject('reserved-ips/'.$reserved_id, new ReservedIP());
	}

	/**
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ReservedIPException
	 * @return ReservedIP[]
	 */
	public function getReservedIPs(?ListOptions $options = null) : array
	{
		return $this->getListObjects('reserved-ips', new ReservedIP(), $options);
	}

	/**
	 * @param $reserved_id - string
	 * @throws ReservedIPException
	 * @return void
	 */
	public function deleteReservedIP(string $reserved_id) : void
	{
		$this->deleteObject('reserved-ips/'.$reserved_id, new ReservedIP());
	}

	/**
	 * @param $region - string - Region identifaction site code.
	 * @param $ip_type - string - v4 or v6
	 * @param $label - string - What shall you name it?
	 * @throws ReservedIPException
	 * @return ReservedIP
	 */
	public function createReservedIP(string $region, string $ip_type, string $label = '') : ReservedIP
	{
		$params = [
			'region' => $region,
			'ip_type' => $ip_type,
		];

		if ($label != '')
		{
			$params['label'] = $label;
		}

		return $this->createObject('reserved-ips', new ReservedIP(), $params);
	}

	public function convertInstanceIP(string $ip_address, ?string $label = null) : ReservedIP
	{

	}

	public function attachReservedIP(ReservedIP $reserved_ip, string $instance_id) : void
	{

	}

	public function detachReservedIP(string $reserved_id) : void
	{

	}
}
