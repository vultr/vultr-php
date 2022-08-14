<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Plans;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds vps plan information
 */
class VPSPlan extends Model
{
	protected string $id;
	protected int $vcpuCount;
	protected int $ram;
	protected int $disk;
	protected int $diskCount;
	protected int $bandwidth;
	protected float $monthlyCost;
	protected string $type;
	protected array $locations;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getVcpuCount() : int
	{
		return $this->vcpuCount;
	}

	public function setVcpuCount(int $vcpu) : void
	{
		$this->vcpuCount = $vcpu;
	}

	public function getRam() : int
	{
		return $this->ram;
	}

	public function setRam(int $ram) : void
	{
		$this->ram = $ram;
	}

	public function getDisk() : int
	{
		return $this->disk;
	}

	public function setDisk(int $size) : void
	{
		$this->disk = $size;
	}

	public function getDiskCount() : int
	{
		return $this->diskCount;
	}

	public function setDiskCount(int $disk_count) : void
	{
		$this->diskCount = $disk_count;
	}

	public function getBandwidth() : int
	{
		return $this->bandwidth;
	}

	public function setBandwidth(int $bandwidth) : void
	{
		$this->bandwidth = $bandwidth;
	}

	public function getMonthlyCost() : float
	{
		return $this->monthlyCost;
	}

	public function setMonthlyCost(float $monthly_cost) : void
	{
		$this->monthlyCost = $monthly_cost;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getLocations() : array
	{
		return $this->locations;
	}

	public function setLocations(array $locations) : void
	{
		$this->locations = $locations;
	}

	public function getResponseListName() : string
	{
		return 'plans';
	}
}
