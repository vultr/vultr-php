<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Plans\VPSPlan;
use Vultr\VultrPhp\Services\Plans\BMPlan;
use Vultr\VultrPhp\Services\Plans\PlanException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

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
			new RequestException('Bad Request', new Request('GET', 'backups'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->plans->getVPSPlans() as $plan)
		{
			$this->assertInstanceOf(VPSPlan::class, $plan);
			$found_plan = null;
			foreach ($plans[$plan->getResponseListName()] as $response)
			{
				if ($response['id'] !== $plan->getId()) continue;
				$found_plan = $response;
			}
			$this->compare($plan, $found_plan);
		}

		$this->expectException(PlanException::class);
		$client->plans->getVPSPlans();
	}

	public function testGetVPSPlansFilter_VC2()
	{
		$this->markTestSkipped('Not implemented');
	}

	public function testGetVPSPlansFilter_VHF()
	{
		$this->markTestSkipped('Not implemented');
	}

	public function testGetVPSPlansFilter_VDC()
	{
		$this->markTestSkipped('Not implemented');
	}

	public function testGetBMPlans()
	{
		$provider = $this->getDataProvider();
		$plans = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($plans)),
			new RequestException('Bad Request', new Request('GET', 'backups'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->plans->getBMPlans() as $plan)
		{
			$this->assertInstanceOf(BMPlan::class, $plan);
			$found_plan = null;
			foreach ($plans[$plan->getResponseListName()] as $response)
			{
				if ($response['id'] !== $plan->getId()) continue;
				$found_plan = $response;
			}
			$this->compare($plan, $found_plan);
		}

		$this->expectException(PlanException::class);
		$client->plans->getBMPlans();
	}

	private function mapRegions(array $regions) : array
	{
		$region_map = [];
		foreach ($regions as $region)
		{
			$region_map[$region['id']] = $region;
		}
		return $region_map;
	}

	private function findPlan(string $id, array $plans) : ?array
	{
		$found_plan = null;
		foreach ($plans as $plan)
		{
			if ($plan['id'] !== $id) continue;
			$found_plan = $plan;
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
				foreach ($region->toArray() as $reg_attr => $reg_val)
				{
					$this->assertEquals($reg_val, $this->region_map[$region->getId()][$reg_attr]);
				}
			}
		}
	}
}
