<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes\Resources;

use Vultr\VultrPhp\Services\Kubernetes\NodeResource;

class LoadBalancerResource extends NodeResource
{
	protected string $type = 'loadbalancer';
}
