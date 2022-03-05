<?php

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

	public function getReservedIPs(?ListOptions $options = null) : array
	{

	}

	public function deleteReservedIP(string $reserved_id) : void
	{

	}

	public function createReservedIP(ReservedIP $reserved_ip) : ReservedIP
	{

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
