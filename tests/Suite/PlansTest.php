<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Plans\BMPlan;
use Vultr\VultrPhp\Services\Plans\PlanException;
use Vultr\VultrPhp\Services\Plans\PlanService;
use Vultr\VultrPhp\Services\Plans\VPSPlan;
use Vultr\VultrPhp\Services\Regions\Region;
use Vultr\VultrPhp\Tests\VultrTest;

class PlansTest extends VultrTest
{
	private $region_map;

	public function setUp() : void
	{
		/**
		 * Do to the nature these being static properties cache for regions.
		 * Only setup the request since this is required for every test.
		 * This avoids leaving a stray buffer when running tests multiple times in repeated succession from guzzle. Since by default it uses php://temp.
		 * I personally did not feel like adding a stream interface to use php://memory so this was added.
		 */
		$provider = $this->getDataProvider();
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($provider->dataGetRegions()))
		]);
		$client->regions->cacheRegions(true);
		$this->region_map = $this->mapRegions($provider->dataGetRegions()['regions']);
	}

	public function testGetPlan()
	{
		$provider = $this->getDataProvider();

		$plans = $provider->dataGetVPSPlans();
		$bm_plans = $provider->dataGetBMPlans();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($bm_plans)),
		]);

		$id_vps = 'vc2-1c-1gb';
		$found_vps_plan = $this->findPlan($id_vps, $plans['plans']);
		$this->assertNotNull($found_vps_plan);

		$id_bm = 'vbm-4c-32gb';
		$found_bm_plan = $this->findPlan($id_bm, $bm_plans['plans_metal']);
		$this->assertNotNull($found_bm_plan);

		$plan = $client->plans->getPlan($id_vps);
		$this->assertInstanceOf(VPSPlan::class, $plan);
		$this->compare($plan, $found_vps_plan);

		$plan = $client->plans->getPlan($id_bm);
		$this->assertInstanceOf(BMPlan::class, $plan);
		$this->compare($plan, $found_bm_plan);
	}

	public function testGetVPSPlans()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		foreach ($client->plans->getVPSPlans() as $plan)
		{
			$this->assertInstanceOf(VPSPlan::class, $plan);
			$found_plan = null;
			foreach ($plans[$plan->getResponseListName()] as $response)
			{
				if ($response['id'] !== $plan->getId()) continue;
				$found_plan = $response;
				break;
			}
			$this->compare($plan, $found_plan);
		}

		$this->expectException(PlanException::class);
		$client->plans->getVPSPlans();
	}

	public function testGetVPSPlansFilter_VC2()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testFilter($client->plans->getVPSPlans(PlanService::FILTER_VC2), $plans, PlanService::FILTER_VC2);

		$this->expectException(PlanException::class);
		$client->plans->getVPSPlans(PlanService::FILTER_VC2);
	}

	public function testGetVPSPlansFilter_VHF()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testFilter($client->plans->getVPSPlans(PlanService::FILTER_VHF), $plans, PlanService::FILTER_VHF);

		$this->expectException(PlanException::class);
		$client->plans->getVPSPlans(PlanService::FILTER_VHF);
	}

	public function testGetVPSPlansFilter_VDC()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testFilter($client->plans->getVPSPlans(PlanService::FILTER_VDC), $plans, PlanService::FILTER_VDC);

		$this->expectException(PlanException::class);
		$client->plans->getVPSPlans(PlanService::FILTER_VDC);
	}

	public function testGetBMPlans()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		foreach ($client->plans->getBMPlans() as $plan)
		{
			$this->assertInstanceOf(BMPlan::class, $plan);
			$found_plan = null;
			foreach ($plans[$plan->getResponseListName()] as $response)
			{
				if ($response['id'] !== $plan->getId()) continue;
				$found_plan = $response;
				break;
			}
			$this->compare($plan, $found_plan);
		}

		$this->expectException(PlanException::class);
		$client->plans->getBMPlans();
	}

	private function testFilter(array $objects, array $plans, string $filter)
	{
		foreach ($objects as $plan)
		{
			$this->assertInstanceOf(VPSPlan::class, $plan);
			$this->assertEquals($plan->getType(), $filter);
			$found_plan = null;
			foreach ($plans[$plan->getResponseListName()] as $response)
			{
				if ($response['id'] !== $plan->getId()) continue;
				$found_plan = $response;
				break;
			}
			$this->compare($plan, $found_plan);
		}
	}

	private function findPlan(string $id, array $plans) : ?array
	{
		$found_plan = null;
		foreach ($plans as $plan)
		{
			if ($plan['id'] !== $id) continue;
			$found_plan = $plan;
			break;
		}
		return $found_plan;
	}

	private function compare(VPSPlan|BMPlan $plan, array $found_plan)
	{
		foreach ($plan->toArray() as $attr => $value)
		{
			if ($attr != 'locations')
			{
				$this->assertEquals($value, $found_plan[$attr], 'Attribute failed to match: '.$attr);
				continue;
			}
			$this->assertIsArray($value);
			foreach ($value as $region)
			{
				$this->assertInstanceOf(Region::class, $region);
				foreach ($region->toArray() as $reg_attr => $reg_val)
				{
					$this->assertEquals($reg_val, $this->region_map[$region->getId()][$reg_attr]);
				}
			}
		}
	}
}
