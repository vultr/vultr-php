<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Regions;

use Exception;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

class RegionService extends VultrService
{
	private static ?array $cache_region = null;

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws RegionException
	 * @return Region[]
	 */
	public function getRegions(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('regions', new Region(), $options);
	}

	/**
	 * @param $id - string - Ex ewr - Id of the region
	 * @param $type - string|null - PlanService Filters - FILTER_ALL, FILTER_VC2, FILTER_VHF, FILTER_VDC, FILTER_VBM
	 * @throws RegionException
	 * @return (VPSPlan|BMPlan)[]
	 */
	public function getAvailablility(string $id, ?string $type = null) : array
	{
		try
		{
			$params = [];
			if ($type !== null)
			{
				$params['type'] = $type;
			}
			$response = $this->getClientHandler()->get('regions/'.$id.'/availability', $params);
		}
		catch (VultrClientException $e)
		{
			throw new RegionException('Failed to get available compute in region: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$plans = [];
		try
		{
			$decode = VultrUtil::decodeJSON((string)$response->getBody(), true);

			foreach ($decode['available_plans'] as $plan_id)
			{
				$plan = $this->getVultrClient()->plans->getPlan($plan_id);
				if ($plan === null) continue; // Not valid plan.
				$plans[] = $plan;
			}
		}
		catch (Exception $e)
		{
			throw new RegionException('Failed to deserialize availability plan objects: '.$e->getMessage(), null, $e);
		}
		return $plans;
	}

	/**
	 * @param $id - string - Ex ewr - Region id.
	 * @throws RegionException
	 * @return Region|null
	 */
	public function getRegion(string $id) : ?Region
	{
		$this->cacheRegions();
		return static::$cache_region[$id] ?? null;
	}

	/**
	 * @param $override - bool - Depending on whether to requery the regions.
	 * @throws RegionException
	 * @return void
	 */
	public function cacheRegions(bool $override = false) : void
	{
		if (static::$cache_region !== null && !$override) return;

		static::$cache_region = [];
		$options = new ListOptions(500);
		foreach ($this->getRegions($options) as $region)
		{
			static::$cache_region[$region->getId()] = $region;
		}
	}
}
