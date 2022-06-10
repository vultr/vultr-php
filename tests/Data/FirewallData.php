<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class FirewallData extends DataProvider
{
	public function getFirewallGroup() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/firewall/dataGetFirewallGroup.json'), true);
	}
}
