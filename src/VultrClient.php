<?php

namespace Vultr\VultrPhp;

// Dependancies.
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;


// Service Handlers
use Vultr\VultrPhp\Account\AccountService;

class VultrClient
{
	private VultrAuth $auth;
	private Client $client;

	public const MAP = [
		'account'          => AccountService::class,
		/**
		'application'      => ApplicationService::class,
		'backups'          => BackupsService::class,
		'baremetal'        => BareMetalService::class,
		'billing'          => BillingService::class,
		'blockstorage'     => BlockStorageService::class,
		'dns'              => DNSService::class,
		'firewall'         => FirewallService::class,
		'instances'        => InstancesService::class,
		'iso'              => ISOService::class,
		'kubernetes'       => KubernetesService::class,
		'loadbalancers'    => LoadBalancersService::class,
		'objectstorage'    => ObjectStorageService::class,
		'operating_system' => OperatingSystemService::class,
		'plans'            => PlansService::class,
		'private_networks' => PrivateNetworksService::class,
		'reserved_ips'     => ReservedIPSService::class,
		'regions'          => RegionsService::class,
		'snapshots'        => SnapshotsService::class,
		'ssh_keys'         => SSHKeysService::class,
		'startup_scripts'  => StartupScriptsService::class,
		'users'            => UsersService::class
		*/
	];

	/**
	 * @param $auth
	 * @see VultrAuth
	 * @param $guzzle_options
	 * @see VultrConfig::generateGuzzleConfig
	 */
	public function __construct(VultrAuth $auth, array $guzzle_options = [])
	{
		$this->auth = $auth;
		$this->client = new Client(VultrConfig::generateGuzzleConfig($auth, $guzzle_options));
	}

	public function __get(string $name)
	{
		$class = self::MAP[$name] ?? null;

		if ($class !== null)
		{
			return new $class($this);
		}

		return null;
	}

	public function get(string $uri, ?array $params = null) : string
	{
		$options = [];
		if ($params !== null)
		{
			$options[RequestOptions::QUERY] = $params;
		}

		try
		{
			$response = $this->client->request('GET', $uri, $options);
		}
		catch (RequestException $e)
		{
			if ($e->hasResponse())
			{
				throw new VultrException('GET failed : '.$e->getMessage(), VultrException::DEFAULT_CODE, null, $e->getResponse()->getStatusCode());
			}
			throw new VultrException('GET failed : '. $e->getMessage());
		}
		catch (Exception $e)
		{
			throw new VultrException('GET failed : '.$e->getMessage());
		}

		return $response;
	}
}
