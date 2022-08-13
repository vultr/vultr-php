<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds backup schedule information
 */
class BackupSchedule extends Model
{
	protected ?bool $enabled = null;
	protected string $type;
	protected ?string $nextScheduledTimeUtc = null;
	protected int $hour;
	protected int $dow; // Day of the week
	protected int $dom; // Day of the month

	public function getEnabled() : ?bool
	{
		return $this->enabled;
	}

	public function setEnabled(bool $enabled) : void
	{
		$this->enabled = $enabled;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getNextScheduledTimeUtc() : ?string
	{
		return $this->nextScheduledTimeUtc;
	}

	public function setNextScheduledTimeUtc(string $nextScheduledTimeUtc) : void
	{
		$this->nextScheduledTimeUtc = $nextScheduledTimeUtc;
	}

	public function getHour() : int
	{
		return $this->hour;
	}

	public function setHour(int $hour) : void
	{
		$this->hour = $hour;
	}

	public function getDow() : int
	{
		return $this->dow;
	}

	public function setDow(int $dow) : void
	{
		$this->dow = $dow;
	}

	public function getDom() : int
	{
		return $this->dom;
	}

	public function setDom(int $dom) : void
	{
		$this->dom = $dom;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('BackupScheduleException', 'InstanceException', parent::getModelExceptionClass());
	}
}
