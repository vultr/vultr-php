<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class ReservedIPsData extends DataProvider
{
	protected function dataGetReservedIP(string $reserved_id) : array
	{
		return json_decode('{
  "reserved_ip": {
	"id": "'.$reserved_id.'",
	"region": "ewr",
	"ip_type": "v4",
	"subnet": "192.0.2.123",
	"subnet_size": 32,
	"label": "Example Reserved IPv4",
	"instance_id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60"
  }
}', true);
	}

	protected function dataGetReservedIPs() : array
	{
		return json_decode('
			{
				"reserved_ips": [
				{
				  "id": "cb676a46-66fd-4dfb-b839-443f2a6c0b60",
				  "region": "ewr",
				  "ip_type": "v4",
				  "subnet": "192.0.2.123",
				  "subnet_size": 32,
				  "label": "Example Reserved IPv4",
				  "instance_id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60"
				},
				{
				  "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
				  "region": "ewr",
				  "ip_type": "v6",
				  "subnet": "2001:0db8:5:5157::",
				  "subnet_size": 64,
				  "label": "Example Reserved IPv6",
				  "instance_id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60"
				}
				],
				"meta": {
				"total": 2,
				"links": {
				  "next": "",
				  "prev": ""
				}
				}
			}
		', true);
	}

	public function dataCreateReservedIP() : array
	{
		return json_decode('
			{
			  "reserved_ip": {
				"id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
				"region": "ewr",
				"ip_type": "v4",
				"subnet": "192.0.2.123",
				"subnet_size": 32,
				"label": "Example Reserved IPv4",
				"instance_id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60"
			  }
			}
		', true);
	}
}
