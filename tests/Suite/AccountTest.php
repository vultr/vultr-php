<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Account\Account;
use Vultr\VultrPhp\Services\Account\AccountException;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\VultrException;

class AccountTest extends VultrTest
{
	public function testGetAccount()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, [], 'Invalid json'),
			new Response(400, [], json_encode(['error' => 'Bad request']))
		]);

		$account = $client->account->getAccount();
		$this->assertInstanceOf(Account::class, $account);
		foreach ($account->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$account->getResponseName()][$attr]);
		}

		try
		{
			$client->account->getAccount();
		}
		catch (VultrException $e)
		{
			$this->assertStringContainsString('Failed to deserialize json', $e->getMessage());
		}

		$this->expectException(AccountException::class);
		$client->account->getAccount();
	}
}
