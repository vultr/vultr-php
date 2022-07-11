<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Util\ModelOptions;

class InstanceCreate extends ModelOptions
{
	/**
	 * @see https://www.vultr.com/api/#operation/create-instance
	 */
	protected string $region;
	protected string $plan;

	// 1 of these has to be specified.
	protected ?int $os_id = null;
	protected ?string $iso_id = null;
	protected ?string $snapshot_id = null;
	protected ?int $app_id = null;
	protected ?string $image_id = null;

	//
	protected ?string $script_id = null;
	protected ?array $sshkey_id = null;
	protected ?string $ipxe_chain_url = null;
	protected ?bool $enable_ipv6 = null;
	protected ?string $backups = null;
	protected ?string $user_data = null;

	protected ?string $firewall_group_id = null;
	protected ?string $reserved_ip = null;

	protected ?bool $ddos_protection = null;
	protected ?bool $activation_email = null;

	protected ?array $attach_vpc = null;
	protected ?bool $enable_vpc = null;

	protected ?string $hostname = null;
	protected ?string $label = null;
	protected ?array $tags = null;

	public function __construct(string $region, string $plan)
	{
		$this->region = $region;
		$this->plan = $plan;
		parent::__construct();
	}
}
