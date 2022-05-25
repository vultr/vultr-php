<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Regions\Region;
use Vultr\VultrPhp\Services\Regions\RegionException;
use Vultr\VultrPhp\Services\Plans\VPSPlan;
use Vultr\VultrPhp\Services\Plans\BMPlan;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

use Vultr\VultrPhp\Tests\VultrTest;

class RegionsTest extends VultrTest
{
	public function testGetRegions()
	{
		$provider = $this->getDataProvider();

		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$map = $this->mapRegions($data['regions']);
		foreach ($client->regions->getRegions() as $region)
		{
			$this->assertInstanceOf(Region::class, $region);
			$this->assertNotNull($map[$region->getId()]);
			foreach ($region->toArray() as $attr => $value)
			{
				$this->assertEquals($value, $map[$region->getId()][$attr]);
			}
		}

		$this->expectException(RegionException::class);
		$client->regions->getRegions();
	}

	public function testGetAvailability()
	{
		$provider = $this->getDataProvider();

		$id = 'ewr';
		$data = $provider->getData($id);
		$regions = $provider->dataGetRegions();
		$vps_plans = $provider->dataGetVPSPlans();
		$bm_plans = $provider->dataGetBMPlans();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($regions)), // Cache regions
			new Response(200, ['Content-Type' => 'application/json'], json_encode($vps_plans)), // Cache Plans
			new Response(200, ['Content-Type' => 'application/json'], json_encode($bm_plans)), // Cache Plans

			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->regions->cacheRegions(true);
		$client->plans->cachePlans(true);
		$map_region = $this->mapRegions($regions['regions']);
		$map_plan = [];
		foreach ([(new VPSPlan())->getResponseListName() => $vps_plans, (new BMPlan())->getResponseListName() => $bm_plans] as $field => $plans)
		{
			foreach ($plans[$field] as $plan)
			{
				$map_plan[$plan['id']] = $plan;
			}
		}

		foreach ($client->regions->getAvailablility($id) as $plan)
		{
			$this->assertContains($plan::class, [VPSPlan::class, BMPlan::class]);
			foreach ($plan->toArray() as $attr => $value)
			{
				if ($attr != 'locations')
				{
					$this->assertEquals($value, $map_plan[$plan->getId()][$attr]);
					continue;
				}
				$this->assertIsArray($value);
				foreach ($value as $region)
				{
					foreach ($region->toArray() as $reg_attr => $reg_val)
					{
						$this->assertEquals($reg_val, $map_region[$region->getId()][$reg_attr]);
					}
				}
			}
		}

		$this->expectException(RegionException::class);
		$client->regions->getAvailablility($id);
	}
}
