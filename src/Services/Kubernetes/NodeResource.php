<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes;

use Vultr\VultrPhp\Util\Model;

class NodeResource extends Model
{
	// This value is not in the response and is set based on the resource returned.
	protected string $type = 'unknown-resource';

	protected string $id;
	protected string $label;
	protected string $dateCreated;
	protected string $status;

	// There is no setter for type as this is set by the child objects.
	public function getType() : string
	{
		return $this->type;
	}

	// Setters & Getters

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $dateCreated) : void
	{
		$this->dateCreated = $dateCreated;
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
		return 'resource';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('NodeResourceException', 'KubernetesException', parent::getModelExceptionClass());
	}
}
