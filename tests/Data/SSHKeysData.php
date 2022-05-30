<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class SSHKeysData extends DataProvider
{
	protected function dataGetSSHKey(string $ssh_id) : array
	{
		return json_decode('{
	"ssh_key": {
		"id": "'.$ssh_id.'",
		"date_created": "2020-10-10T01:56:30+00:00",
		"name": "Example SSH Key",
		"ssh_key": "ssh-rsa AA... user@example.com"
	}
}', true);
	}

	protected function dataGetSSHKeys() : array
	{
		return json_decode('
		{
			"ssh_keys": [
				{
					"id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
					"date_created": "2020-10-10T01:56:20+00:00",
					"name": "Example SSH Key",
					"ssh_key": "ssh-rsa AA... user@example.com"
				},
				{
					"id": "cb676a46-66fd-4dfb-b839-asdasd14141",
					"date_created": "2020-10-10T01:56:20+00:00",
					"name": "Example SSH Key",
					"ssh_key": "ssh-rsa AA... user@example.com"
				},
				{
					"id": "cb676a46-66fd-4dfb-b839-asfsdf24141",
					"date_created": "2020-10-10T01:56:20+00:00",
					"name": "Example SSH Key",
					"ssh_key": "ssh-rsa AA... user@example.com"
				}
			],
			"meta": {
				"total": 3,
				"links": {
					"next": "",
					"prev": ""
				}
			}
		}', true);
	}
}
