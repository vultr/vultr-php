<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;
use Vultr\VultrPhp\Tests\Data\PlansData;

class RegionsData extends DataProvider
{
	public function dataGetRegions() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-regions.json'), true);
	}

	public function dataGetAvailability() : array
	{
		return [
			"available_plans" => [
				"vbm-4c-32gb",
				"vbm-6c-32gb",
				"vbm-24c-256gb-amd",
				"vc2-1c-1gb",
				"vc2-1c-2gb",
				"vc2-2c-4gb",
				"vc2-4c-8gb",
				"vc2-6c-16gb",
				"vc2-8c-32gb",
				"vc2-16c-64gb",
				"vc2-24c-96gb",
				"vdc-2c-8gb",
				"vdc-4c-16gb",
				"vhf-1c-1gb",
				"vhf-1c-2gb",
				"vhf-2c-2gb",
				"vhf-2c-4gb"
			]
		];
	}

	public function dataGetVPSPlans() : array
	{
		return (new PlansData())->dataGetVPSPlans();
	}

	public function dataGetBMPlans() : array
	{
		return (new PlansData())->dataGetBMPlans();
	}
}
