<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Users\User;
use Vultr\VultrPhp\Services\Users\UserException;
use Vultr\VultrPhp\Tests\VultrTest;

class UsersTest extends VultrTest
{
	public function testGetUser()
	{
		$user_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data = $this->getDataProvider()->getData($user_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
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
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new User(), $client->users->getUsers(), $data);

		$this->expectException(UserException::class);
		$client->users->getUsers();
	}

	public function testCreateUser()
	{
		$user = new User();
		$user->setEmail('test@example.com');
		$user->setName('Test Vultr');

		$data = [];
		$data['user'] = $user->toArray();
		$data['user']['id'] = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data['user']['api_enabled'] = true;
		$data['user']['acls'] = [];
		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$user = $client->users->createUser('test12345', $user);
		foreach ($user->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['user'][$attr]);
		}

		$this->expectException(UserException::class);
		$client->users->createUser('Password1234', $user);
	}

	public function testUpdateUser()
	{
		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($this->getDataProvider()->dataGetUser($id))),
			new Response(204, ['Content-Type' => 'text/html']),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$user = $client->users->getUser($id);
		$user->setEmail('updated@example.com');
		$user->setName('Obi Wan');

		$client->users->updateUser($user);

		$this->expectException(UserException::class);
		$client->users->updateUser($user);
	}

	public function testDeleteUser()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204, ['Content-Type' => 'text/html']),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->users->deleteUser($id);

		$this->expectException(UserException::class);
		$client->users->deleteUser($id);
	}
}
