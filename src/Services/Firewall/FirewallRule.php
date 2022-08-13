<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Firewall;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds firewall rule information.
 */
class FirewallRule extends Model
{
	protected int $id;
	protected string $ipType;
	protected string $action;
	protected string $protocol;
	protected string $port;
	protected string $subnet;
	protected int $subnetSize;
	protected string $source;
	protected string $notes;

	public function getId() : int
	{
		return $this->id;
	}

	public function setId(int $id) : void
	{
		$this->id = $id;
	}

	public function getIpType() : string
	{
		return $this->ipType;
	}

	public function setIpType(string $ip_type) : void
	{
		$this->ipType = $ip_type;
	}

	public function getAction() : string
	{
		return $this->action;
	}

	public function setAction(string $action) : void
	{
		$this->action = $action;
	}

	public function getProtocol() : string
	{
		return $this->protocol;
	}

	public function setProtocol(string $protocol) : void
	{
		$this->protocol = $protocol;
	}

	public function getPort() : string
	{
		return $this->port;
	}

	public function setPort(string $port) : void
	{
		$this->port = $port;
	}

	public function getSubnet() : string
	{
		return $this->subnet;
	}

	public function setSubnet(string $subnet) : void
	{
		if (strpos($subnet, '/') !== false)
		{
			list($ip, $bit) = explode('/', $subnet);
			$subnet = $ip;
			$this->setSubnetSize((int)$bit);
		}

		$this->subnet = $subnet;
	}

	public function getSubnetSize() : int
	{
		return $this->subnetSize;
	}

	public function setSubnetSize(int $subnet_size) : void
	{
		$this->subnetSize = $subnet_size;
	}

	public function getSource() : string
	{
		return $this->source;
	}

	public function setSource(string $source) : void
	{
		$this->source = $source;
	}

	public function getNotes() : string
	{
		return $this->notes;
	}

	public function setNotes(string $notes) : void
	{
		$this->notes = $notes;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('FirewallRuleException', 'FirewallException', parent::getModelExceptionClass());
	}
}

