<?php

declare(strict_types=1);

namespace Vultr\VultrPhp;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use ReflectionClass;
use ReflectionProperty;
use Throwable;
use Vultr\VultrPhp\Services;

/**
 * @property-read Services\Account\AccountService $account
 * @property-read Services\Applications\ApplicationService $applications
 * @property-read Services\Backups\BackupService $backups
 * @property-read Services\BareMetal\BareMetalService $baremetal
 * @property-read Services\Billing\BillingService $billing
 * @property-read Services\BlockStorage\BlockStorageService $blockstorage
 * @property-read Services\DNS\DNSService $dns
 * @property-read Services\Firewall\FirewallService $firewall
 * @property-read Services\Instances\InstanceService $instances
 * @property-read Services\ISO\ISOService $iso
 * @property-read Services\Kubernetes\KubernetesService $kubernetes
 * @property-read Services\LoadBalancers\LoadBalancerService $loadbalancers
 * @property-read Services\ObjectStorage\ObjectStorageService $objectstorage
 * @property-read Services\OperatingSystems\OperatingSystemService $operating_system
 * @property-read Services\Plans\PlanService $plans
 * @property-read Services\ReservedIP\ReservedIPService $reserved_ip
 * @property-read Services\Regions\RegionService $regions
 * @property-read Services\Snapshots\SnapshotService $snapshots
 * @property-read Services\SSHKeys\SSHKeyService $ssh_keys
 * @property-read Services\StartupScripts\StartupScriptService $startup_scripts
 * @property-read Services\Users\UserService $users
 * @property-read Services\VPC\VPCService $vpc
 */
class VultrClient
{
	private VultrClientHandler $client;

	/**
	 * @var int[]
	 */
	private array $service_props = [];

	////
	// Service Properties Start
	// @codingStandardsIgnoreStart
	////

	private Services\Account\AccountService $_account;
	private Services\Applications\ApplicationService $_applications;
	private Services\Backups\BackupService $_backups;
	private Services\BareMetal\BareMetalService $_baremetal;
	private Services\Billing\BillingService $_billing;
	private Services\BlockStorage\BlockStorageService $_blockstorage;
	private Services\DNS\DNSService $_dns;
	private Services\Firewall\FirewallService $_firewall;
	private Services\Instances\InstanceService $_instances;
	private Services\ISO\ISOService $_iso;
	private Services\Kubernetes\KubernetesService $_kubernetes;
	private Services\LoadBalancers\LoadBalancerService $_loadbalancers;
	private Services\ObjectStorage\ObjectStorageService $_objectstorage;
	private Services\OperatingSystems\OperatingSystemService $_operating_system;
	private Services\Plans\PlanService $_plans;
	private Services\ReservedIP\ReservedIPService $_reserved_ip;
	private Services\Regions\RegionService $_regions;
	private Services\Snapshots\SnapshotService $_snapshots;
	private Services\SSHKeys\SSHKeyService $_ssh_keys;
	private Services\StartupScripts\StartupScriptService $_startup_scripts;
	private Services\Users\UserService $_users;
	private Services\VPC\VPCService $_vpc;

	////
	// Service Properties End
	// @codingStandardsIgnoreEnd
	////

	/**
	 * @param $http - PSR18 ClientInterface - https://www.php-fig.org/psr/psr-18/
	 * @param $request - PSR17 RequestFactoryInterface - https://www.php-fig.org/psr/psr-17/#21-requestfactoryinterface
	 * @param $response - PSR17 ResponseFactoryInterface - https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface
	 * @param $stream - PSR17 StreamFactoryInterface - https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface
	 */
	private function __construct(
		string $API_KEY,
		?ClientInterface $http = null,
		?RequestFactoryInterface $request = null,
		?ResponseFactoryInterface $response = null,
		?StreamFactoryInterface $stream = null
	)
	{
		try
		{
			$this->setClientHandler($API_KEY, $http, $request, $response, $stream);
		}
		catch (Throwable $e)
		{
			throw new VultrException('Failed to initialize client: '.$e->getMessage(), VultrException::DEFAULT_CODE, null, $e);
		}

		foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PRIVATE) as $property)
		{
			$type_name = $property->getType()->getName();
			if (stripos($type_name, 'Services') === false) continue;

			$prop_name = $property->getName();
			$this->$prop_name = new $type_name($this, $this->client);
			$this->service_props[$prop_name] = 1;
		}
	}

	/**
	 * The entry point into using the client and all its related services.
	 */
	public static function create(
		string $API_KEY,
		?ClientInterface $http = null,
		?RequestFactoryInterface $request = null,
		?ResponseFactoryInterface $response = null,
		?StreamFactoryInterface $stream = null
	) : VultrClient
	{
		return new VultrClient($API_KEY, $http, $request, $response, $stream);
	}

	/**
	 * Magic method that allows interaction with readonly properties that house service handlers.
	 *
	 * Client will return null if the service object does not exist.
	 */
	public function __get(string $name) : mixed
	{
		$name = '_'.$name;
		return ($this->service_props[$name] ?? null) !== null ? $this->$name : null;
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

	protected function setClientHandler(
		string $API_KEY,
		?ClientInterface $http = null,
		?RequestFactoryInterface $request = null,
		?ResponseFactoryInterface $response = null,
		?StreamFactoryInterface $stream = null
	) : void
	{
		$this->client = new VultrClientHandler(
			new VultrAuth($API_KEY),
			$http ?: Psr18ClientDiscovery::find(),
			$request ?: Psr17FactoryDiscovery::findRequestFactory(),
			$response ?: Psr17FactoryDiscovery::findResponseFactory(),
			$stream ?: Psr17FactoryDiscovery::findStreamFactory()
		);
	}
}
