<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds instance reverse dns ipv6 information
 */
class InstanceReverseIPv6 extends Model
{
	protected string $ip;
	protected string $reverse;

	public function getIp() : string
	{
		return $this->ip;
	}

	public function setIp(string $ip) : void
	{
		$this->ip = $ip;
	}

	public function getReverse() : string
	{
		return $this->reverse;
	}

	public function setReverse(string $reverse) : void
	{
		$this->reverse = $reverse;
	}

	public function getResponseName() : string
	{
		return 'reverse_ipv6';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('InstanceReverseIPv6Exception', 'InstanceException', parent::getModelExceptionClass());
	}
}
