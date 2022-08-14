<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds load balancer health information.
 */
class LBHealth extends Model
{
	protected string $protocol;
	protected int $port;
	protected string $path;
	protected int $checkInterval;
	protected int $responseTimeout;
	protected int $unhealthyThreshold;
	protected int $healthyThreshold;

	public function getProtocol() : string
	{
		return $this->protocol;
	}

	public function setProtocol(string $protocol) : void
	{
		$this->protocol = $protocol;
	}

	public function getPort() : int
	{
		return $this->port;
	}

	public function setPort(int $port) : void
	{
		$this->port = $port;
	}

	public function getPath() : string
	{
		return $this->path;
	}

	public function setPath(string $path) : void
	{
		$this->path = $path;
	}

	public function getCheckInterval() : int
	{
		return $this->checkInterval;
	}

	public function setCheckInterval(int $check_interval) : void
	{
		$this->checkInterval = $check_interval;
	}

	public function getResponseTimeout() : int
	{
		return $this->responseTimeout;
	}

	public function setResponseTimeout(int $response_timeout) : void
	{
		$this->responseTimeout = $response_timeout;
	}

	public function getUnhealthyThreshold() : int
	{
		return $this->unhealthyThreshold;
	}

	public function setUnhealthyThreshold(int $unhealthy_threshold) : void
	{
		$this->unhealthyThreshold = $unhealthy_threshold;
	}

	public function getHealthyThreshold() : int
	{
		return $this->healthyThreshold;
	}

	public function setHealthyThreshold(int $healthy_threshold) : void
	{
		$this->healthyThreshold = $healthy_threshold;
	}
}
