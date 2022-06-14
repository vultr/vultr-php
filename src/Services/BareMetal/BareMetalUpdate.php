<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

class BareMetalUpdate extends BareMetalOptions
{
	/**
	 * @see https://www.vultr.com/api/#operation/update-baremetal
	 */
	protected ?string $user_data = null;
	protected ?string $label = null;
	protected ?int $os_id = null;
	protected ?int $app_id = null;
	protected ?int $image_id = null;
	protected ?bool $enable_ipv6 = null;
	protected ?array $tags = null;
}
