<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds node information.
 */
class Node extends Model
{
	protected string $id;
	protected string $label;
	protected string $dateCreated;
	protected string $status;

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

	public function getModelExceptionClass() : string
	{
		return str_replace('NodeException', 'KubernetesException', parent::getModelExceptionClass());
	}
}
