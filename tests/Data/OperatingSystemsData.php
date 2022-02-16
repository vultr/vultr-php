<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class OperatingSystemsData extends DataProvider
{
	public function dataGetOperatingSystems() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/v2-get-operatingsystems.json'), true);
	}

	public function dataGetOperatingSystem() : array
	{
		return $this->dataGetOperatingSystems();
	}
}
