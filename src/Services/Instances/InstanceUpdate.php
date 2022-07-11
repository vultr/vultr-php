<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\ModelOptions;

class InstanceUpdate extends ModelOptions
{
	/**
	 * @see https://www.vultr.com/api/#operation/update-instance
	 */
	protected ?int $app_id = null;
	protected ?string $image_id = null;
	protected ?int $os_id = null;

	protected ?string $plan = null;

	protected ?string $backups = null;
	protected ?string $firewall_group_id = null;
	protected ?string $enable_ipv6 = null;
	protected ?string $user_data = null;
	protected ?bool $ddos_protection = null;

	protected ?bool $enable_vpc = null;
	protected ?array $attach_vpc = null;
	protected ?array $detach_vpc = null;

	protected ?string $label = null;
	protected ?array $tags = null;
}
