<?php

namespace Vultr\VultrPhp\Services\ReservedIPs;

use Vultr\VultrPhp\Util\Model;
use Vultr\VultrPhp\Services\Regions\Region;

class ReservedIP extends Model
{
	protected string $id;
	protected string $region;
	protected string $ipType;
	protected string $subnet;
	protected int $subnetSize;
	protected string $label;
	protected string $instanceId;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getIpType() : string
	{
		return $this->ipType;
	}

	public function setIpType(string $ip_type) : void
	{
		$this->ipType = $ip_type;
	}

	public function getSubnet() : string
	{
		return $this->subnet;
	}

	public function setSubnet(string $subnet) : void
	{
		$this->subnet = $subnet;
	}

	public function getSubnetSize() : int
	{
		return $this->subnetSize;
	}

	public function setSubnetSize(int $subnet_size) : void
	{
		$this->subnetSize = $subnet_size;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getInstanceId() : string
	{
		return $this->instanceId;
	}

	public function setInstanceId(string $instance_id) : void
	{
		$this->instanceId = $instance_id;
	}

	public function getResponseName() : string
	{
		return 'reserved_ip';
	}
}
