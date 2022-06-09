<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ObjectStorage;

use Vultr\VultrPhp\Util\Model;

class ObjStoreCluster extends Model
{
	protected string $id;
	protected string $region;
	protected string $hostname;
	protected string $deploy;

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

	public function getHostname() : string
	{
		return $this->hostname;
	}

	public function setHostname(string $hostname) : void
	{
		$this->hostname = $hostname;
	}

	public function isDeployed() : bool
	{
		return $this->getDeploy() === 'yes';
	}

	public function getDeploy() : string
	{
		return $this->deploy;
	}

	public function setDeploy(string $deploy) : void
	{
		$this->deploy = $deploy;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('ObjStoreClusterException', 'ObjectStorageException', parent::getModelExceptionClass());
	}

	public function getResponseName() : string
	{
		return 'cluster';
	}
}

