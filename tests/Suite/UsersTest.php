<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Users\UserException;
use Vultr\VultrPhp\Services\Users\User;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class UsersTest extends VultrTest
{
	public function testGetUser()
	{
		$user_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data = $this->getDataProvider()->getData($user_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'users/'.$user_id), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$user = $client->users->getUser($user_id);
		foreach ($user->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['user'][$attr]);
		}

		$this->expectException(UserException::class);
		$client->users->getUser($user_id);
	}

	public function testGetUsers()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'users'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->users->getUsers() as $user)
		{
			$this->assertInstanceOf(User::class, $user);
			foreach ($data['users'] as $object)
			{
				if ($user->getId() != $object['id']) continue;

				foreach ($user->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
				break;
			}
		}

		$this->expectException(UserException::class);
		$client->users->getUsers();
	}

	public function testCreateUser()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testUpdateUser()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteUser()
	{
		$this->markTestSkipped('Incomplete');
	}
}
