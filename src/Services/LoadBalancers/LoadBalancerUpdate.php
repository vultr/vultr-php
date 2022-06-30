<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\ModelOptions;

class LoadBalancerUpdate extends ModelOptions
{
	protected ?SSL $ssl = null;
	protected ?StickySession $sticky_session = null;
	protected ?array $forwarding_rules = null;
	protected ?LBHealth $health_check = null;
	protected ?bool $proxy_protocol = null;
	protected ?bool $ssl_redirect = null;
	protected ?string $balancing_algorithm = null;
	protected ?array $instances = null;
	protected ?string $label = null;
	protected ?string $vpc = null;
	protected ?array $firewall_rules = null;
}
