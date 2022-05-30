<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ISO;

use Vultr\VultrPhp\Util\Model;

class ISO extends Model
{
	public string $id;
	public string $dateCreated;
	public string $filename;
	public int $size;
	public string $md5sum;
	public string $sha512sum;
	public string $status;

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

	public function getFilename() : string
	{
		return $this->filename;
	}

	public function setFilename(string $file_name) : void
	{
		$this->filename = $filename;
	}

	public function getSize() : int
	{
		return $this->size;
	}

	public function setSize(int $size) : void
	{
		$this->size = $size;
	}

	public function getMd5sum() : string
	{
		return $this->md5sum;
	}

	public function setMd5sum(string $md5sum) : void
	{
		$this->md5sum = $md5sum;
	}

	public function getSha512sum() : string
	{
		return $this->sha512sum;
	}

	public function setSha512sum(string $sha512sum) : void
	{
		$this->sha512sum = $sha512sum;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getResponseName() : string
	{
		return 'iso';
	}
}
