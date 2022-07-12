<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes\Resources;

use Vultr\VultrPhp\Services\Kubernetes\NodeResource;

class BlockResource extends NodeResource
{
	protected string $type = 'blockstorage';
}
