<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Util\ModelOptions;

/**
 * Options to create a baremetal machine.
 *
 * @see https://www.vultr.com/api/#operation/create-baremetal
 */
class BareMetalCreate extends ModelOptions
{
	// Required Parameters.
	protected string $region;
	protected string $plan;

	// 1 of these are required.
	protected ?int $os_id = null;
	protected ?string $snapshot_id = null;
	protected ?int $app_id = null;
	protected ?string $image_id = null;

	// Optional Parameters.
	protected ?string $script_id = null;
	protected ?bool $enable_ipv6 = null;
	protected ?array $sshkey_id = null;
	protected ?string $user_data = null;
	protected ?string $label = null;
	protected ?bool $activation_email = null;
	protected ?string $hostname = null;
	protected ?string $reserved_ip = null;
	protected ?bool $persistent_pxe = null;
	protected ?array $tags = null;

	public function __construct(string $region, string $plan)
	{
		$this->region = $region;
		$this->plan = $plan;
		parent::__construct();
	}
}
