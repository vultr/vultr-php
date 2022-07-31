<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Backups;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds snapshot information on the backup.
 */
class Backup extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $description;
	protected int $size;
	protected string $status;

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

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}
}
