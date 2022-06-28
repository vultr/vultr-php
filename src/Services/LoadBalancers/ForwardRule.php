<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

class ForwardRule extends Model
{
	protected string $id;
	protected string $frontendProtocol;
	protected int $frontendPort;
	protected string $backendProtocol;
	protected int $backendPort;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getFrontendProtocol() : string
	{
		return $this->frontendProtocol;
	}

	public function setFrontendProtocol(string $frontend_protocol) : void
	{
		$this->frontendProtocol = $frontend_protocol;
	}

	public function getFrontendPort() : int
	{
		return $this->frontendPort;
	}

	public function setFrontendPort(int $frontend_port) : void
	{
		$this->frontendPort = $frontend_port;
	}

	public function getBackendProtocol() : string
	{
		return $this->backendProtocol;
	}

	public function setBackendProtocol(string $backend_protocol) : void
	{
		$this->backendProtocol = $backend_protocol;
	}

	public function getBackendPort() : int
	{
		return $this->backendPort;
	}

	public function setBackendPort(int $backend_port) : void
	{
		$this->backendPort = $backend_port;
	}

	public function getResponseName() : string
	{
		return 'forwarding_rule';
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('ForwardRuleException', 'LoadBalancerException', parent::getModelExceptionClass());
	}
}
