<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes\Resources;

use Vultr\VultrPhp\Services\Kubernetes\NodeResource;

/**
 * Block storage level resource that is being used by the node pool
 */
class BlockResource extends NodeResource
{
	protected string $type = 'blockstorage';
}
