<?php

namespace Vultr\VultrPhp\Services\BlockStorage;

use Vultr\VultrPhp\Util\Model;

class BlockStorage extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected float $cost;
	protected string $status;
	protected int $sizeGb;
	protected string $region;
	protected string $attachedToInstance;
	protected string $label;
	protected string $mountId;

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

	public function getCost() : float
	{
		return $this->cost;
	}

	public function setCost(float $cost) : void
	{
		$this->cost = $cost;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getAttachedToInstance() : string
	{
		return $this->attachedToInstance;
	}

	public function setAttachedToInstance(string $attached_to_instance) : void
	{
		$this->attachedToInstance = $attached_to_instance;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getMountId() : string
	{
		return $this->mountId;
	}

	public function setMountId(string $mount_id) : void
	{
		$this->mountId = $mount_id;
	}
}

