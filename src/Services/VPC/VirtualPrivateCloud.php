<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\VPC;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds virtual private cloud information.
 */
class VirtualPrivateCloud extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $region;
	protected string $description;
	protected string $v4Subnet;
	protected int $v4SubnetMask;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $date_created) : void
	{
		$this->dateCreated = $date_created;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getV4Subnet() : string
	{
		return $this->v4Subnet;
	}

	public function setV4Subnet(string $v4_subnet) : void
	{
		$this->v4Subnet = $v4_subnet;
	}

	public function getV4SubnetMask() : int
	{
		return $this->v4SubnetMask;
	}

	public function setV4SubnetMask(int $v4_subnet_mask) : void
	{
		$this->v4SubnetMask = $v4_subnet_mask;
	}

	public function getResponseName() : string
	{
		return 'vpc';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('VirtualPrivateCloudException', 'VPCException', parent::getModelExceptionClass());
	}

	public function getUpdateParams() : array
	{
		return ['description'];
	}
}
