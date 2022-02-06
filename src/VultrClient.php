<?php

namespace Vultr\VultrPhp;

use GuzzleHttp\Client;

// Service Handlers
use Vultr\VultrPhp\Services\Account\AccountService;
use Vultr\VultrPhp\Services\Applications\ApplicationService;
use Vultr\VultrPhp\Services\Backups\BackupService;
use Vultr\VultrPhp\Services\Regions\RegionService;
use Vultr\VultrPhp\Services\Snapshots\SnapshotService;

class VultrClient
{
	private VultrAuth $auth;
	private Client $client;

	public const MAP = [
		'account'          => AccountService::class,
		'applications'     => ApplicationService::class,
		'backups'          => BackupService::class,
		//'baremetal'        => BareMetalService::class, // TODO
		//'billing'          => BillingService::class, // TODO
		//'blockstorage'     => BlockStorageService::class, // TODO
		//'dns'              => DNSService::class, // TODO
		//'firewall'         => FirewallService::class, // TODO
		//'instances'        => InstanceService::class, // TODO
		//'iso'              => ISOService::class, // TODO
		//'kubernetes'       => KubernetesService::class, // TODO
		//'loadbalancers'    => LoadBalancerService::class, // TODO
		//'objectstorage'    => ObjectStorageService::class, // TODO
		//'operating_system' => OperatingSystemService::class, // TODO
		//'plans'            => PlanService::class, // TODO
		//'reserved_ips'     => ReservedIPSService::class, // TODO
		'regions'          => RegionService::class, // TODO
		'snapshots'        => SnapshotService::class, // TODO
		//'ssh_keys'         => SSHKeyService::class, // TODO
		//'startup_scripts'  => StartupScriptService::class, // TODO
		//'users'            => UserService::class, // TODO
		//'vpc'              => VPCService::class, // TODO
	];

	/**
	 * @param $auth
	 * @see VultrAuth
	 * @param $guzzle_options
	 * @see VultrConfig::generateGuzzleConfig
	 */
	private function __construct(VultrAuth $auth, array $guzzle_options = [])
	{
		$this->auth = $auth;
		$this->client = new Client(VultrConfig::generateGuzzleConfig($auth, $guzzle_options));
	}

	public function __get(string $name)
	{
		$class = self::MAP[$name] ?? null;

		if ($class !== null)
		{
			return new $class($this->auth, $this->client);
		}

		return null;
	}

	public static function create(string $API_KEY, array $guzzle_options = []) : VultrClient
	{
		return new VultrClient(new VultrAuth($API_KEY), $guzzle_options);
	}
}
