<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

class InstanceIPv6Info extends Model
{
	protected string $ip;
	protected string $network;
	protected int $networkSize;
	protected string $type;

	public function getIp() : string
	{
		return $this->ip;
	}

	public function setIp(string $ip) : void
	{
		$this->ip = $ip;
	}

	public function getNetwork() : string
	{
		return $this->network;
	}

	public function setNetwork(string $network) : void
	{
		$this->network = $network;
	}

	public function getNetworkSize() : int
	{
		return $this->networkSize;
	}

	public function setNetworkSize(int $networkSize) : void
	{
		$this->networkSize = $networkSize;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function setType(string $type) : void
	{
		$this->type = $type;
	}

	public function getResponseName() : string
	{
		return 'ipv6';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('InstanceIPv6InfoException', 'InstanceException', parent::getModelExceptionClass());
	}
}
