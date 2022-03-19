<?php

namespace Vultr\VultrPhp\Services\ISO;

use Vultr\VultrPhp\Util\Model;

class PublicISO extends Model
{
	protected string $id;
	protected string $name;
	protected string $description;
	protected string $md5sum;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
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

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getMd5sum() : string
	{
		return $this->md5sum;
	}

	public function setMd5sum(string $md5sum) : void
	{
		$this->md5sum = $md5sum;
	}

	public function getResponseListName() : string
	{
		return 'public_isos';
	}
}
