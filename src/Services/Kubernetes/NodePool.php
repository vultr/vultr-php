<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds node pool information.
 */
class NodePool extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected string $dateUpdated;
	protected string $label;
	//protected string $tag; Doesn't exist, in the actual response.
	protected string $plan;
	protected string $status;
	protected int $nodeQuantity;
	protected int $minNodes;
	protected int $maxNodes;
	protected bool $autoScaler;

	/** @var Node[] */
	protected array $nodes;

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

	public function setDateCreated(string $dateCreated) : void
	{
		$this->dateCreated = $dateCreated;
	}

	public function getDateUpdated() : string
	{
		return $this->dateUpdated;
	}

	public function setDateUpdated(string $dateUpdated) : void
	{
		$this->dateUpdated = $dateUpdated;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getPlan() : string
	{
		return $this->plan;
	}

	public function setPlan(string $plan) : void
	{
		$this->plan = $plan;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getNodeQuantity() : int
	{
		return $this->nodeQuantity;
	}

	public function setNodeQuantity(int $nodeQuantity) : void
	{
		$this->nodeQuantity = $nodeQuantity;
	}

	public function getMinNodes() : int
	{
		return $this->minNodes;
	}

	public function setMinNodes(int $minNodes) : void
	{
		$this->minNodes = $minNodes;
	}

	public function getMaxNodes() : int
	{
		return $this->maxNodes;
	}

	public function setMaxNodes(int $maxNodes) : void
	{
		$this->maxNodes = $maxNodes;
	}

	public function getAutoScaler() : bool
	{
		return $this->autoScaler;
	}

	public function setAutoScaler(bool $autoScaler) : void
	{
		$this->autoScaler = $autoScaler;
	}

	public function getNodes() : array
	{
		return $this->nodes;
	}

	public function setNodes(array $nodes) : void
	{
		$this->nodes = $nodes;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('NodePoolException', 'KubernetesException', parent::getModelExceptionClass());
	}
}

