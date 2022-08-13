<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\SSHKeys;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds ssh key information.
 */
class SSHKey extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $name;
	protected string $sshKey;

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

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getSshKey() : string
	{
		return $this->sshKey;
	}

	public function setSshKey(string $ssh_key) : void
	{
		$this->sshKey = $ssh_key;
	}

	public function getResponseName() : string
	{
		return 'ssh_key';
	}

	public function getUpdateParams() : array
	{
		return ['name', 'ssh_key'];
	}
}
