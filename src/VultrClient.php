<?php

namespace Vultr\VultrPhp;

// Dependancies.
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;


// Service Handlers
use Vultr\VultrPhp\Account\AccountService;
use Vultr\VultrPhp\Applications\ApplicationService;
use Vultr\VultrPhp\Backups\BackupService;
use Vultr\VultrPhp\Regions\RegionService;
use Vultr\VultrPhp\Snapshots\SnapshotService;

// Utils
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Util\ListOptions;

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
			return new $class($this);
		}

		return null;
	}

	public static function create(string $API_KEY, array $guzzle_options = []) : VultrClient
	{
		return new VultrClient(new VultrAuth($API_KEY), $guzzle_options);
	}

	public function get(string $uri, ?array $params = null)
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
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$message = $e->getMessage();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}

				throw new VultrException('GET failed : '.$message, VultrException::DEFAULT_CODE, null, $response->getStatusCode());
			}
			throw new VultrException('GET failed : '. $e->getMessage());
		}
		catch (Exception $e)
		{
			throw new VultrException('GET failed : '.$e->getMessage());
		}

		return $response;
	}

	public function list(string $uri, ModelInterface $model, ListOptions $options, ?array $params = null) : array
	{
		try
		{
			if ($params === null)
			{
				$params = [];
			}
			$params['per_page'] = $options->getPerPage();

			if ($options->getCurrentCursor() != '')
			{
				$params['cursor'] = $options->getCurrentCursor();
			}

			$response = $this->get($uri, $params);
		}
		catch (VultrException $e)
		{
			throw new VultrClientException('Failed to list: '.$e->getMessage(), $e->getHTTPCode());
		}

		$objects = [];
		try
		{
			$stdclass = json_decode($response->getBody());
			$options->setTotal($stdclass->meta->total);
			$options->setNextCursor($stdclass->meta->links->next);
			$options->setPrevCursor($stdclass->meta->links->prev);
			$list_name = $model->getResponseListName();
			foreach ($stdclass->$list_name as $object)
			{
				$objects[] = VultrUtil::mapObject($object, $model);
			}
		}
		catch (Exception $e)
		{
			throw new VultrClientException('Failed to deserialize list: '. $e->getMessage());
		}

		return $objects;
	}
}
