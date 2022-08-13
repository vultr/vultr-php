<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds iso mount status information
 */
class IsoStatus extends Model
{
	protected string $state;
	protected ?string $isoId = null;

	public function getState() : string
	{
		return $this->state;
	}

	public function setState(string $state) : void
	{
		$this->state = $state;
	}

	public function getIsoId() : ?string
	{
		return $this->isoId;
	}

	public function setIsoId(string $iso_id) : void
	{
		$this->isoId = $iso_id;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('IsoStatusException', 'InstanceException', parent::getModelExceptionClass());
	}
}
