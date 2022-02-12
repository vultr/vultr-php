<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class RegionsData extends DataProvider
{
	public function dataGetRegions() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-regions.json'), true);
	}
}
