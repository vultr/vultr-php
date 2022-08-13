<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes\Resources;

use Vultr\VultrPhp\Services\Kubernetes\NodeResource;

/**
 * Load balancer level resource that is being used by the node pool
 */
class LoadBalancerResource extends NodeResource
{
	protected string $type = 'loadbalancer';
}
