<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Firewall;

use Vultr\VultrPhp\Util\Model;

class FirewallGroup extends Model
{
	protected string $id;
	protected string $description;
	protected string $dateCreated;
	protected string $dateModified;
	protected int $instanceCount;
	protected int $ruleCount;
	protected int $maxRuleCount;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
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

	public function getInstanceCount() : int
	{
		return $this->instanceCount;
	}

	public function setInstanceCount(int $instance_count) : void
	{
		$this->instanceCount = $instance_count;
	}

	public function getRuleCount() : int
	{
		return $this->ruleCount;
	}

	public function setRuleCount(int $rule_count) : void
	{
		$this->ruleCount = $rule_count;
	}

	public function getMaxRuleCount() : int
	{
		return $this->maxRuleCount;
	}

	public function setMaxRuleCount(int $max_rule_count) : void
	{
		$this->maxRuleCount = $max_rule_count;
	}

	public function getUpdateParams() : array
	{
		return ['description'];
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('FirewallGroupException', 'FirewallException', parent::getModelExceptionClass());
	}
}

