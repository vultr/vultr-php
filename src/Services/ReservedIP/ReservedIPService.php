<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ReservedIP;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

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

	/**
	 * @see https://www.vultr.com/api/#tag/reserved-ip/operation/convert-reserved-ip
	 * @param $ip_address - string - Example: 192.168.0.1
	 * @param $label - string|null
	 * @throws ReservedIPException
	 * @throws VultrException
	 * @return ReservedIP
	 */
	public function convertInstanceIP(string $ip_address, ?string $label = null) : ReservedIP
	{
		$client = $this->getClientHandler();

		$params = [
			'ip_address' => $ip_address
		];

		if ($label !== null)
		{
			$params['label'] = $label;
		}

		try
		{
			$response = $client->post('reserved-ips/convert', $params);
		}
		catch (VultrClientException $e)
		{
			throw new ReservedIPException('Failed to convert instance ip: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new ReservedIP();
		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/reserved-ip/operation/attach-reserved-ip
	 * @param $reserved_ip - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $instance_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws ReservedIPException
	 * @return void
	 */
	public function attachReservedIP(string $reserved_ip, string $instance_id) : void
	{
		$client = $this->getClientHandler();

		try
		{
			$client->post('reserved-ips/'.$reserved_ip.'/attach', ['instance_id' => $instance_id]);
		}
		catch (VultrClientException $e)
		{
			throw new ReservedIPException('Failed to attach reserved ip: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @see https://www.vultr.com/api/#tag/reserved-ip/operation/detach-reserved-ip
	 * @param $reserved_ip - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws ReservedIPException
	 * @return void
	 */
	public function detachReservedIP(string $reserved_ip) : void
	{
		$client = $this->getClientHandler();

		try
		{
			$client->post('reserved-ips/'.$reserved_ip.'/detach');
		}
		catch (VultrClientException $e)
		{
			throw new ReservedIPException('Failed to detach reserved ip: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}
