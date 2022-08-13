<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Util\ModelOptions;

/**
 * Options to update a baremetal machine.
 *
 * @see https://www.vultr.com/api/#operation/update-baremetal
 */
class BareMetalUpdate extends ModelOptions
{
	protected ?string $user_data = null;
	protected ?string $label = null;
	protected ?int $os_id = null;
	protected ?int $app_id = null;
	protected ?int $image_id = null;
	protected ?bool $enable_ipv6 = null;
	protected ?array $tags = null;
}
