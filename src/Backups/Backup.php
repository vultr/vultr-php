<?php

namespace Vultr\VultrPhp\Backups;

use Vultr\VultrPhp\Util\Model;

class Backup extends Model
{
	protected int $id;
	protected string $dateCreated;
	protected string $description;
	protected int $size;
	protected string $status;

	public function getId() : int
	{
		return $this->id;
	}

	public function setId(int $id) : void
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

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}
}
