<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\OperatingSystems;

use Vultr\VultrPhp\Util\Model;

class OperatingSystem extends Model
{
	protected int $id;
	protected string $name;
	protected string $arch;
	protected string $family;

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

	public function getArch() : string
	{
		return $this->arch;
	}

	public function setArch(string $arch) : void
	{
		$this->arch = $arch;
	}

	public function getFamily() : string
	{
		return $this->family;
	}

	public function setFamily(string $family) : void
	{
		$this->family = $family;
	}

	public function getResponseListName() : string
	{
		return 'os';
	}
}
