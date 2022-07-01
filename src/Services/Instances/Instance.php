<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

class Instance extends Model
{
	protected string $id;
	protected string $os;
	protected int $ram;
	protected int $disk;
	protected string $mainIp;
	protected string $internalIp;
	protected int $vcpuCount;
	protected string $region;
	protected ?string $defaultPassword = null;
	protected string $dateCreated;
	protected string $status;
	protected string $powerStatus;
	protected string $serverStatus;
	protected int $allowedBandwidth;
	protected string $netmaskV4;
	protected string $gatewayV4;
	protected string $v6Network;
	protected string $v6MainIp;
	protected int $v6NetworkSize;
	protected string $hostname;
	protected string $label;
	protected string $kvm;
	protected int $osId;
	protected int $appId;
	protected string $imageId;
	protected string $firewallGroupId;
	protected array $features;
	protected string $plan;
	protected array $tags;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getOs() : string
	{
		return $this->os;
	}

	public function setOs(string $os) : void
	{
		$this->os = $os;
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

	public function setDisk(int $disk) : void
	{
		$this->disk = $disk;
	}

	public function getMainIp() : string
	{
		return $this->mainIp;
	}

	public function setMainIp(string $main_ip) : void
	{
		$this->mainIp = $main_ip;
	}

	public function getInternalIp() : string
	{
		return $this->internalIp;
	}

	public function setInternalIp(string $internal_ip) : void
	{
		$this->internalIp = $internal_ip;
	}

	public function getVcpuCount() : int
	{
		return $this->vcpuCount;
	}

	public function setVcpuCount(int $vcpu_count) : void
	{
		$this->vcpuCount = $vcpu_count;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getDefaultPassword() : ?string
	{
		return $this->defaultPassword;
	}

	public function setDefaultPassword(string $default_password) : void
	{
		$this->defaultPassword = $default_password;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $date_created) : void
	{
		$this->dateCreated = $date_created;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getPowerStatus() : string
	{
		return $this->powerStatus;
	}

	public function setPowerStatus(string $power_status) : void
	{
		$this->powerStatus = $power_status;
	}

	public function getServerStatus() : string
	{
		return $this->serverStatus;
	}

	public function setServerStatus(string $server_status) : void
	{
		$this->serverStatus = $server_status;
	}

	public function getAllowedBandwidth() : int
	{
		return $this->allowedBandwidth;
	}

	public function setAllowedBandwidth(int $allowed_bandwidth) : void
	{
		$this->allowedBandwidth = $allowed_bandwidth;
	}

	public function getNetmaskV4() : string
	{
		return $this->netmaskV4;
	}

	public function setNetmaskV4(string $netmask_v4) : void
	{
		$this->netmaskV4 = $netmask_v4;
	}

	public function getGatewayV4() : string
	{
		return $this->gatewayV4;
	}

	public function setGatewayV4(string $gateway_v4) : void
	{
		$this->gatewayV4 = $gateway_v4;
	}

	public function getV6Network() : string
	{
		return $this->v6Network;
	}

	public function setV6Network(string $v6_network) : void
	{
		$this->v6Network = $v6_network;
	}

	public function getV6MainIp() : string
	{
		return $this->v6MainIp;
	}

	public function setV6MainIp(string $v6_main_ip) : void
	{
		$this->v6MainIp = $v6_main_ip;
	}

	public function getV6NetworkSize() : int
	{
		return $this->v6NetworkSize;
	}

	public function setV6NetworkSize(int $v6_network_size) : void
	{
		$this->v6NetworkSize = $v6_network_size;
	}

	public function getHostname() : string
	{
		return $this->hostname;
	}

	public function setHostname(string $hostname) : void
	{
		$this->hostname = $hostname;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getKvm() : string
	{
		return $this->kvm;
	}

	public function setKvm(string $kvm) : void
	{
		$this->kvm = $kvm;
	}

	public function getOsId() : int
	{
		return $this->osId;
	}

	public function setOsId(int $os_id) : void
	{
		$this->osId = $os_id;
	}

	public function getAppId() : int
	{
		return $this->appId;
	}

	public function setAppId(int $app_id) : void
	{
		$this->appId = $app_id;
	}

	public function getImageId() : string
	{
		return $this->imageId;
	}

	public function setImageId(string $image_id) : void
	{
		$this->imageId = $image_id;
	}

	public function getFirewallGroupId() : string
	{
		return $this->firewallGroupId;
	}

	public function setFirewallGroupId(string $firewall_group_id) : void
	{
		$this->firewallGroupId = $firewall_group_id;
	}

	public function getFeatures() : array
	{
		return $this->features;
	}

	public function setFeatures(array $features) : void
	{
		$this->features = $features;
	}

	public function getPlan() : string
	{
		return $this->plan;
	}

	public function setPlan(string $plan) : void
	{
		$this->plan = $plan;
	}

	public function getTags() : array
	{
		return $this->tags;
	}

	public function setTags(array $tags) : void
	{
		$this->tags = $tags;
	}
}
