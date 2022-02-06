<?php

namespace Vultr\VultrPhp\Services\Snapshots;

use Vultr\VultrPhp\Util\Model;

class Snapshot extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $description;
	protected int $size;
	protected int $compressedSize;
	protected string $status;
	protected int $osId;
	protected int $appId;

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

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getSize() : int
	{
		return $this->size;
	}

	public function setSize(int $size) : void
	{
		$this->size = $size;
	}

	public function getCompressedSize() : int
	{
		return $this->compressedSize;
	}

	public function setCompressedSize(int $compressed_size) : void
	{
		$this->compressedSize = $compressed_size;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
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
}
