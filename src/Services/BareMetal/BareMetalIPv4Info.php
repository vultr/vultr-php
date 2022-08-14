<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds ipv4 address information on the ip address attached to the baremetal machine.
 */
class BareMetalIPv4Info extends Model
{
	// The response data.
	protected string $ip;
	protected string $netmask;
	protected string $gateway;
	protected string $type;
	protected string $reverse;

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

	public function getResponseName() : string
	{
		return 'ipv4';
	}
}
