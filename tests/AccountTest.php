<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Account\Account;
use Vultr\VultrPhp\Services\Account\AccountException;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
	public function testGetAccount()
	{
		$data = [
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

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, [], 'Invalid json'),
			new RequestException('This is an exception', new Request('GET', 'account')),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		$account = $client->account->getAccount();
		$this->assertInstanceOf(Account::class, $account);
		foreach ($account->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['account'][$attr]);
		}

		try
		{
			$client->account->getAccount();
		}
		catch (AccountException $e)
		{
			$this->assertStringContainsString('Failed to deserialize account object', $e->getMessage());
		}

		$this->expectException(AccountException::class);
		$client->account->getAccount();
	}
}
