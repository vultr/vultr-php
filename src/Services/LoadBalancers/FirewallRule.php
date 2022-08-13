<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds firewall rule information
 */
class FirewallRule extends Model
{
	protected string $id;
	protected int $port;
	protected string $source;
	protected string $ipType;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getPort() : int
	{
		return $this->port;
	}

	public function setPort(int $port) : void
	{
		$this->port = $port;
	}

	public function getSource() : string
	{
		return $this->source;
	}

	public function setSource(string $source) : void
	{
		$this->source = $source;
	}

	public function getIpType() : string
	{
		return $this->ipType;
	}

	public function setIpType(string $ip_type) : void
	{
		$this->ipType = $ip_type;
	}
}
