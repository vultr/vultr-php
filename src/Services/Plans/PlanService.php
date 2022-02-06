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

	public function cachePlans(bool $override = false) : void
	{
		if (static::$cache_plans !== null && !$override) return;

		static::$cache_plans = [];
		$options = new ListOptions(500);
		$bm_plans = $this->getBMPlans($options);
		$options = new ListOptions(500);
		$vps_plans = $this->getVPSPlans(null, null, $options);

		foreach ([$bm_plans, $vps_plans] as $plans)
		{
			foreach ($plans as $plan)
			{
				static::$cache_plans[$plan->getId()] = $plan;
			}
		}
	}

	public function getPlan(string $id) : VPSPlan|BMPlan|null
	{
		$this->cachePlans();
		return static::$cache_plans[$id] ?? null;
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
