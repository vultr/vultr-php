<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\Model;

class VPCAttachment extends Model
{
	protected string $id;
	protected string $macAddress;
	protected string $ipAddress;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getMacAddress() : string
	{
		return $this->macAddress;
	}

	public function setMacAddress(string $mac_address) : void
	{
		$this->macAddress = $mac_address;
	}

	public function getIpAddress() : string
	{
		return $this->ipAddress;
	}

	public function setIpAddress(string $ip_address) : void
	{
		$this->ipAddress = $ip_address;
	}

	public function getResponseName() : string
	{
		return 'vpc';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('VPCAttachmentException', 'InstanceException', parent::getModelExceptionClass());
	}
}
