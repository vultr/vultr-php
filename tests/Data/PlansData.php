<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Services\Plans\PlanService;

use Vultr\VultrPhp\Tests\DataProvider;
use Vultr\VultrPhp\Tests\Data\RegionsData;
class PlansData extends DataProvider
{
	public function dataGetVPSPlans() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-vpsplans.json'), true);
	}

	public function dataGetVPSPlansFilter_VC2() : array
	{
		return $this->filterVPSPlans(PlanService::FILTER_VC2);
	}

	public function dataGetVPSPlansFilter_VHF() : array
	{
		return $this->filterVPSPlans(PlanService::FILTER_VHF);
	}

	public function dataGetVPSPlansFilter_VDC() : array
	{
		return $this->filterVPSPlans(PlanService::FILTER_VDC);
	}

	public function dataGetBMPlans() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-plans-metal.json'), true);
	}

	public function dataGetRegions() : array
	{
		return (new RegionsData())->dataGetRegions();
	}

	private function filterVPSPlans(string $type) : array
	{
		$decode = json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-vpsplans.json'), true);

		$data = [
			'plans' => [],
			'meta' => [
				'total' => 0,
				'links' => [
					'next' => '',
					'prev' => '',
				]
			]
		];

		foreach ($decode['plans'] as $plan)
		{
			if ($plan['type'] != $type) continue;

			$data['plans'][] = $plan;
		}

		$data['meta']['total'] = count($data['plans']);

		return $data;
	}
}
