<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds load balanacer information, that balances the traffic.
 */
class LBInfo extends Model
{
	protected string $balancingAlgorithm;
	protected bool $sslRedirect;
	protected StickySession $stickySessions;
	protected bool $proxyProtocol;
	protected string $vpc;

	public function getBalancingAlgorithm() : string
	{
		return $this->balancingAlgorithm;
	}

	public function setBalancingAlgorithm(string $balancing_algorithm) : void
	{
		$this->balancingAlgorithm = $balancing_algorithm;
	}

	public function getSslRedirect() : bool
	{
		return $this->sslRedirect;
	}

	public function setSslRedirect(bool $ssl_redirect) : void
	{
		$this->sslRedirect = $ssl_redirect;
	}

	public function getStickySessions() : StickySession
	{
		return $this->stickySessions;
	}

	public function setStickySessions(StickySession $sticky_sessions) : void
	{
		$this->stickySessions = $sticky_sessions;
	}

	public function getProxyProtocol() : bool
	{
		return $this->proxyProtocol;
	}

	public function setProxyProtocol(bool $proxy_protocol) : void
	{
		$this->proxyProtocol = $proxy_protocol;
	}

	public function getVpc() : string
	{
		return $this->vpc;
	}

	public function setVpc(string $vpc) : void
	{
		$this->vpc = $vpc;
	}
}
