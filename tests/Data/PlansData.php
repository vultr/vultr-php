<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;
use Vultr\VultrPhp\Tests\Data\RegionsData;
class PlansData extends DataProvider
{
	public function dataGetVPSPlans() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-vpsplans.json'), true);
	}

	public function dataGetBMPlans() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-plans-metal.json'), true);
	}

	public function dataGetRegions() : array
	{
		return (new RegionsData())->dataGetRegions();
	}
}
