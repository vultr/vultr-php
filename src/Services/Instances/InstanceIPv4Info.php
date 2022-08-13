<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds instance ipv4 address information
 */
class InstanceIPv4Info extends Model
{
	protected string $ip;
	protected string $netmask;
	protected string $gateway;
	protected string $type;
	protected string $reverse;
	protected ?string $macAddress = null;

	public function getIp() : string
	{
		return $this->ip;
	}

	public function setIp(string $ip) : void
	{
		$this->ip = $ip;
	}

	public function getNetmask() : string
	{
		return $this->netmask;
	}

	public function setNetmask(string $netmask) : void
	{
		$this->netmask = $netmask;
	}

	public function getGateway() : string
	{
		return $this->gateway;
	}

	public function setGateway(string $gateway) : void
	{
		$this->gateway = $gateway;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getReverse() : string
	{
		return $this->reverse;
	}

	public function setReverse(string $reverse) : void
	{
		$this->reverse = $reverse;
	}

	public function getMacAddress() : ?string
	{
		return $this->macAddress;
	}

	public function setMacAddress(string $macAddress) : void
	{
		$this->macAddress = $macAddress;
	}

	public function getResponseName() : string
	{
		return 'ipv4';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('InstanceIPv4InfoException', 'InstanceException', parent::getModelExceptionClass());
	}
}
