<?php

namespace Vultr\VultrPhp\Services\StartupScripts;

use Vultr\VultrPhp\Util\Model;

class StartupScript extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $dateModified;
	protected string $name;
	protected string $type;
	protected string $script;

	public function getId(): string
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

	public function getDateModified() : string
	{
		return $this->dateModified;
	}

	public function setDateModified(string $date_modified) : void
	{
		$this->dateModified = $date_modified;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getScript() : string
	{
		return $this->script;
	}

	public function setScript(string $script) : void
	{
		$this->script = $script;
	}

	public function getUpdateParams() : array
	{
		return ['name', 'script', 'type'];
	}
}
