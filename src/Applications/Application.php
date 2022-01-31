<?php

namespace Vultr\VultrPhp\Applications;

use Vultr\VultrPhp\Util\Model;

class Application extends Model
{
	protected int $id;
	protected string $name;
	protected string $shortName;
	protected string $deployName;
	protected string $type;
	protected string $vendor;
	protected string $imageId;

	public function getId() : int
	{
		return $this->id;
	}

	public function setId(int $id) : void
	{
		$this->id = $id;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getShortName() : string
	{
		return $this->shortName;
	}

	public function setShortName(string $short_name) : void
	{
		$this->shortName = $short_name;
	}

	public function getDeployName() : string
	{
		return $this->deployName;
	}

	public function setDeployName(string $deploy_name) : void
	{
		$this->deployName = $deploy_name;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getVendor() : string
	{
		return $this->vendor;
	}

	public function setVendor(string $vendor) : void
	{
		$this->vendor = $vendor;
	}

	public function getImageId() : string
	{
		return $this->imageId;
	}

	public function setImageId(string $image_id) : void
	{
		$this->imageId = $image_id;
	}
}
