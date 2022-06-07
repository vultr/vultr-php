<?php

declare(strict_types=1);

namespace Vultr\VultrPhp;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;
use Vultr\VultrPhp\Services;

class VultrClient
{
	private VultrClientHandler $client;

	public const MAP = [
		'account'          => Services\Account\AccountService::class,
		'applications'     => Services\Applications\ApplicationService::class,
		'backups'          => Services\Backups\BackupService::class,
		'baremetal'        => Services\BareMetal\BareMetalService::class, // TODO
		'billing'          => Services\Billing\BillingService::class,
		'blockstorage'     => Services\BlockStorage\BlockStorageService::class,
		'dns'              => Services\DNS\DNSService::class,
		'firewall'         => Services\Firewall\FirewallService::class, // TODO
		'instances'        => Services\Instances\InstanceService::class, // TODO
		'iso'              => Services\ISO\ISOService::class,
		'kubernetes'       => Services\Kubernetes\KubernetesService::class, // TODO, do load balancers, and block storage before this.
		'loadbalancers'    => Services\LoadBalancers\LoadBalancerService::class, // TODO, do firewall before this
		'objectstorage'    => Services\ObjectStorage\ObjectStorageService::class, // TODO
		'operating_system' => Services\OperatingSystems\OperatingSystemService::class,
		'plans'            => Services\Plans\PlanService::class,
		'reserved_ips'     => Services\ReservedIPs\ReservedIPService::class, // TODO
		'regions'          => Services\Regions\RegionService::class,
		'snapshots'        => Services\Snapshots\SnapshotService::class,
		'ssh_keys'         => Services\SSHKeys\SSHKeyService::class,
		'startup_scripts'  => Services\StartupScripts\StartupScriptService::class,
		'users'            => Services\Users\UserService::class,
		'vpc'              => Services\VPC\VPCService::class, // TODO
	];

	/**
	 * Optimization
	 */
	private $class_cache = [];

	/**
	 * @param $http - PSR18 ClientInterface - https://www.php-fig.org/psr/psr-18/
	 * @param $request - PSR17 RequestFactoryInterface - https://www.php-fig.org/psr/psr-17/#21-requestfactoryinterface
	 * @param $response - PSR17 ResponseFactoryInterface - https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface
	 * @param $stream - PSR17 StreamFactoryInterface - https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface
	 */
	private function __construct(
		VultrAuth $auth,
		?ClientInterface $http = null,
		?RequestFactoryInterface $request = null,
		?ResponseFactoryInterface $response = null,
		?StreamFactoryInterface $stream = null
	)
	{
		$this->client = new VultrClientHandler(
			$auth,
			$http ?: Psr18ClientDiscovery::find(),
			$request ?: Psr17FactoryDiscovery::findRequestFactory(),
			$response ?: Psr17FactoryDiscovery::findResponseFactory(),
			$stream ?: Psr17FactoryDiscovery::findStreamFactory()
		);
	}

	public function __get(string $name)
	{
		$class = self::MAP[$name] ?? null;

		if ($class !== null)
		{
			return $this->class_cache[$class] ?? ($this->class_cache[$class] = new $class($this, $this->client));
		}

		return null;
	}

	public function setClient(ClientInterface $http) : void
	{
		$this->client->setClient($http);
	}

	public function setRequestFactory(RequestFactoryInterface $request) : void
	{
		$this->client->setRequestFactory($request);
	}

	public function setResponseFactory(ResponseFactoryInterface $response) : void
	{
		$this->client->setResponseFactory($response);
	}

	public function setStreamFactory(StreamFactoryInterface $stream) : void
	{
		$this->client->setStreamFactory($stream);
	}

	public static function create(
		string $API_KEY,
		?ClientInterface $http = null,
		?RequestFactoryInterface $request = null,
		?ResponseFactoryInterface $response = null,
		?StreamFactoryInterface $stream = null
	) : VultrClient
	{
		try
		{
			$client = new VultrClient(new VultrAuth($API_KEY), $http, $request, $response, $stream);
		}
		catch (Throwable $e)
		{
			throw new VultrException('Failed to initialize client: '.$e->getMessage(), VultrException::DEFAULT_CODE, null, $e);
		}

		return $client;
	}
}
