<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\SSHKeys\SSHKeyException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class SSHKeysTest extends VultrTest
{
	public function testGetSSHKey()
	{
		$ssh_id = '3b8066a7-b438-455a-9688-44afc9a3597f';
		$data = $this->getDataProvider()->getData($ssh_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'ssh-keys/'.$ssh_id), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$ssh_key = $client->ssh_keys->getSSHKey($ssh_id);
		foreach ($ssh_key->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$ssh_key->getResponseName()][$attr]);
		}

		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->getSSHKey($ssh_id);
	}

	public function testGetSSHKeys()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testUpdateSSHKey()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteSSHKey()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testCreateSSHKey()
	{
		$this->markTestSkipped('Incomplete');
	}
}
