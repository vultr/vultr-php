<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class BareMetalData extends DataProvider
{
	public function getBaremetalData() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/baremetal/dataGetBareMetal.json'), true);
	}
}
