<?php

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\ModelOptions;

class LoadBalancerCreate extends ModelOptions
{
	/**
	 * @see https://www.vultr.com/api/#operation/create-load-balancer
	 */
	protected string $region;
	protected ?string $balancing_algorithm = null;
	protected ?bool $ssl_redirect = null;
	protected ?bool $proxy_protocol = null;
	protected ?LBHealth $health_check = null;
	protected ?array $forwarding_rules = null;
	protected ?array $firewall_rules = null;
	protected ?StickySession $sticky_session = null;
	protected ?SSL $ssl = null;
	protected ?string $label = null;
	protected ?array $instances = null;
	protected ?string $vpc = null;

	public function __construct(string $region)
	{
		$this->region = $region;
		parent::__construct();
	}
}
