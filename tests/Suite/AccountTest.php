<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Account\Account;
use Vultr\VultrPhp\Services\Account\AccountException;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class AccountTest extends VultrTest
{
	public function testGetAccount()
	{
		$data = $this->getDataProvider()->getData();

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, [], 'Invalid json'),
			new RequestException('This is an exception', new Request('GET', 'account'), new Response(400, [], json_encode(['error' => 'Bad request']))),
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
