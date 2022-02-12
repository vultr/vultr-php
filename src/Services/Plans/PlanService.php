<?php

namespace Vultr\VultrPhp\Services\Plans;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class PlanService extends VultrService
{
	public const FILTER_ALL = 'all';
	public const FILTER_VC2 = 'vc2';
	public const FILTER_VHF = 'vhf';
	public const FILTER_VDC = 'vdc';
	public const FILTER_VBM = 'vbm';

	public const FILTER_WINDOWS = 'windows';

	private static ?array $cache_plans = null;

	/**
	 * @param $type - string|null - FILTER_ALL, FILTER_VC2, FILTER_VHF, FILTER_VDC
	 * @param $os - string|null - FILTER_WINDOWS
	 * @param $options - ListOptions - Interact via reference.
	 * @throws PlanException
	 * @return VPSPlan[]
	 */
	public function getVPSPlans(?string $type = null, ?string $os = null, ?ListOptions &$options = null) : array
	{
		$plans = [];
		try
		{
			if ($options === null)
			{
				$options = new ListOptions(100);
			}

			$params = [];

			if ($type !== null)
			{
				$params['type'] = $type;
			}

			if ($os !== null)
			{
				$params['os'] = $os;
			}

			$plans = $this->list('plans', new VPSPlan(), $options, $params);
			$this->setPlanLocations($plans);
		}
		catch (VultrServiceException $e)
		{
			throw new PlanException('Failed to get vps plans: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $plans;
	}

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws PlanException
	 * @return BMPlan[]
	 */
	public function getBMPlans(?ListOptions &$options = null) : array
	{
		$plans = [];
		try
		{
			if ($options === null)
			{
				$options = new ListOptions(100);
			}
			$plans = $this->list('plans-metal', new BMPlan(), $options);
			$this->setPlanLocations($plans);
		}
		catch (VultrServiceException $e)
		{
			throw new PlanException('Failed to get baremetal plans: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
		return $plans;
	}

	/**
	 * @param $id - string - Ex vc2-1c-1gb - This can be a vps plan id or a baremetal plan id.
	 * @throws PlanException
	 * @return VPSPlan|BMPlan|null
	 */
	public function getPlan(string $id) : VPSPlan|BMPlan|null
	{
		$this->cachePlans();
		return static::$cache_plans[$id] ?? null;
	}

	/**
	 * @param $override - bool - Depending on whether to requery the plans.
	 * @throws PlanException
	 * @return void
	 */
	public function cachePlans(bool $override = false) : void
	{
		if (static::$cache_plans !== null && !$override) return;

		static::$cache_plans = [];
		$options = new ListOptions(500);
		$vps_plans = $this->getVPSPlans(null, null, $options);
		$options = new ListOptions(500);
		$bm_plans = $this->getBMPlans($options);

		foreach ([$bm_plans, $vps_plans] as $plans)
		{
			foreach ($plans as $plan)
			{
				static::$cache_plans[$plan->getId()] = $plan;
			}
		}
	}

	private function setPlanLocations(array &$plans) : void
	{
		$region_service = $this->getVultrClient()->regions;
		$region_service->cacheRegions();
		foreach ($plans as $plan)
		{
			$regions = [];
			foreach ($plan->getLocations() as $id)
			{
				$region = $region_service->getRegion($id);

				if ($region === null) continue; // Cache failure?

				$regions[] = $region;
			}
			$plan->setLocations($regions);
		}
	}
}
