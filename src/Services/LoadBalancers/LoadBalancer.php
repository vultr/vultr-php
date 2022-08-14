<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds load balancer information.
 */
class LoadBalancer extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $region;
	protected string $label;
	protected string $status;
	protected string $ipv4;
	protected string $ipv6;
	protected LBInfo $genericInfo;
	protected LBHealth $healthCheck;
	protected bool $hasSsl;
	protected array $forwardingRules;
	protected array $firewallRules;
	protected array $instances;

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

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getIpv4() : string
	{
		return $this->ipv4;
	}

	public function setIpv4(string $ipv4) : void
	{
		$this->ipv4 = $ipv4;
	}

	public function getIpv6() : string
	{
		return $this->ipv6;
	}

	public function setIpv6(string $ipv6) : void
	{
		$this->ipv6 = $ipv6;
	}

	public function getGenericInfo() : LBInfo
	{
		return $this->genericInfo;
	}

	public function setGenericInfo(LBInfo $generic_info) : void
	{
		$this->genericInfo = $generic_info;
	}

	public function getHealthCheck() : LBHealth
	{
		return $this->healthCheck;
	}

	public function setHealthCheck(LBHealth $health_check) : void
	{
		$this->healthCheck = $health_check;
	}

	public function getHasSsl() : bool
	{
		return $this->hasSsl;
	}

	public function setHasSsl(bool $has_ssl) : void
	{
		$this->hasSsl = $has_ssl;
	}

	public function getForwardingRules() : array
	{
		return $this->forwardingRules;
	}

	public function setForwardingRules(array $forwarding_rules) : void
	{
		$this->forwardingRules = $forwarding_rules;
	}

	public function getFirewallRules() : array
	{
		return $this->firewallRules;
	}

	public function setFirewallRules(array $firewall_rules) : void
	{
		$this->firewallRules = $firewall_rules;
	}

	public function getInstances() : array
	{
		return $this->instances;
	}

	public function setInstances(array $instances) : void
	{
		$this->instances = $instances;
	}
}
