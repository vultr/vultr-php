<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class UsersData extends DataProvider
{
	public function dataGetUser(string $id) : array
	{
		return [
			"user" => [
				"id"          => $id,
				"name"        => "newman update",
				"email"       => "noreply@vultr.com",
				"api_enabled" => true,
				"acls"        => [
					"subscriptions_view",
					"subscriptions",
					"support",
					"provisioning",
					"dns",
					"abuse",
					"upgrade",
					"firewall",
					"alerts",
					"objstore"
				]
			]
		];
	}
}
