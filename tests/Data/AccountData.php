<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class AccountData extends DataProvider
{
	protected function dataGetAccount() : array
	{
		return [
			'account' => [
				'name' => 'Example Account',
				'email' => 'admin@example.com',
				'acls' => [
					'manage_users', 'subscriptions_view', 'subscriptions', 'billing',
					'support', 'provisioning', 'dns', 'abuse', 'upgrade',
					'firewall', 'alerts', 'objstore', 'loadbalancer'
				],
				'balance' => -100.55,
				'pending_charges' => 60.25,
				'last_payment_date' => '2020-1010T01:56:20+00:00',
				'last_payment_amount' => -1.25
			]
		];
	}
}
