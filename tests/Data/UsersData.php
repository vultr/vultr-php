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

	public function dataGetUsers() : array
	{
		return [
			'users' => [
				[
					"id"          => "2e6ba53d-bec6-4f15-8b2d-bd8e6cd62ecf",
					"name"        => "newman update",
					"email"       => "noreply+2@vultr.com",
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
				],
				[
					"id"          => "2e6ba53d-bec6-4f15-132a-bd8e6cd62ecf",
					"name"        => "newman update",
					"email"       => "noreply+3@vultr.com",
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
				],
				[
					"id"          => "2e6ba53d-bec6-4f15-8b2d-ads141141514",
					"name"        => "newman update",
					"email"       => "noreply+4@vultr.com",
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
			],
			'meta' => [
				'total' => 3,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];
	}
}
