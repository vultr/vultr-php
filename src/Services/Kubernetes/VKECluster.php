<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes;

use Vultr\VultrPhp\Util\Model;

class VKECluster extends Model
{
	protected string $id;
	protected string $label;
	protected string $dateCreated;
	protected string $clusterSubnet;
	protected string $serviceSubnet;
	protected string $ip;
	protected string $endpoint;
	protected string $version;
	protected string $region;
	protected string $status;

	/** @var NodePool[] */
	protected array $nodePools;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $dateCreated) : void
	{
		$this->dateCreated = $dateCreated;
	}

	public function getClusterSubnet() : string
	{
		return $this->clusterSubnet;
	}

	public function setClusterSubnet(string $clusterSubnet) : void
	{
		$this->clusterSubnet = $clusterSubnet;
	}

	public function getServiceSubnet() : string
	{
		return $this->serviceSubnet;
	}

	public function setServiceSubnet(string $serviceSubnet) : void
	{
		$this->serviceSubnet = $serviceSubnet;
	}

	public function getIp() : string
	{
		return $this->ip;
	}

	public function setIp(string $ip) : void
	{
		$this->ip = $ip;
	}

	public function getEndpoint() : string
	{
		return $this->endpoint;
	}

	public function setEndpoint(string $endpoint) : void
	{
		$this->endpoint = $endpoint;
	}

	public function getVersion() : string
	{
		return $this->version;
	}

	public function setVersion(string $version) : void
	{
		$this->version = $version;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getNodePools() : array
	{
		return $this->nodePools;
	}

	public function setNodePools(array $nodePools) : void
	{
		$this->nodePools = $nodePools;
	}

	public function getResponseName() : string
	{
		return 'vke_cluster';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('VKEClusterException', 'KubernetesException', parent::getModelExceptionClass());
	}
}

