<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Vultr\VultrPhp\Util\Model;

class Record extends Model
{
	protected string $id;
	protected string $type;
	protected string $name;
	protected string $data;
	protected int $priority;
	protected int $ttl;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getData() : string
	{
		return $this->data;
	}

	public function setData(string $data) : void
	{
		$this->data = $data;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public function setPriority(int $priority) : void
	{
		$this->priority = $priority;
	}

	public function getTtl() : int
	{
		return $this->ttl;
	}

	public function setTtl(int $ttl) : void
	{
		$this->ttl = $ttl;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('RecordException', 'DNSException', parent::getModelExceptionClass());
	}
}
