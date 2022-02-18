<?php

namespace Vultr\VultrPhp;

use Exception;
use GuzzleHttp\Client;

use Vultr\VultrPhp\Services;
use Vultr\VultrPhp\Util\ModelInterface;

class VultrClient
{
	private VultrAuth $auth;
	private Client $client;

	public const MAP = [
		'account'          => Services\Account\AccountService::class,
		'applications'     => Services\Applications\ApplicationService::class,
		'backups'          => Services\Backups\BackupService::class,
		'baremetal'        => Services\BareMetal\BareMetalService::class, // TODO
		'billing'          => Services\Billing\BillingService::class, // TODO
		'blockstorage'     => Services\Blockstorage\BlockstorageService::class, // TODO
		'dns'              => Services\DNS\DNSService::class, // TODO
		'firewall'         => Services\Firewall\FirewallService::class, // TODO
		'instances'        => Services\Instances\InstanceService::class, // TODO
		'iso'              => Services\ISO\ISOService::class, // TODO
		//'kubernetes'       => Services\Kubernetes\KubernetesService::class, // TODO
		//'loadbalancers'    => Services\LoadBalancers\LoadBalancerService::class, // TODO
		//'objectstorage'    => Services\ObjectStorage\ObjectStorageService::class, // TODO
		'operating_system' => Services\OperatingSystems\OperatingSystemService::class,
		'plans'            => Services\Plans\PlanService::class,
		//'reserved_ips'     => Services\ReservedIPs\ReservedIPsService::class, // TODO
		'regions'          => Services\Regions\RegionService::class,
		'snapshots'        => Services\Snapshots\SnapshotService::class,
		//'ssh_keys'         => Services\SSHKeys\SSHKeyService::class, // TODO
		//'startup_scripts'  => Services\StartupScripts\StartupScriptService::class, // TODO
		//'users'            => Services\Users\UserService::class, // TODO
		//'vpc'              => Services\PrivateNetworks\VPCService::class, // TODO
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
			return new $class($this, $this->client);
		}

		return null;
	}

	public static function create(string $API_KEY, array $guzzle_options = []) : VultrClient
	{
		try
		{
			$client = new VultrClient(new VultrAuth($API_KEY), $guzzle_options);
		}
		catch (Exception $e)
		{
			throw new VultrClientException('Failed to initialize client: '.$e->getMessage(), null, $e);
		}

		return $client;
	}
}
