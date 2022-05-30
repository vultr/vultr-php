<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\SSHKeys\SSHKey;
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
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ssh_key = $client->ssh_keys->getSSHKey($ssh_id);
		$this->assertInstanceOf(SSHKey::class, $ssh_key);
		foreach ($ssh_key->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$ssh_key->getResponseName()][$attr]);
		}

		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->getSSHKey($ssh_id);
	}

	public function testGetSSHKeys()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$keys = $client->ssh_keys->getSSHKeys();
		$this->assertIsArray($keys);
		$this->assertEquals($data['meta']['total'], count($keys));
		foreach ($keys as $ssh_key)
		{
			$this->assertInstanceOf(SSHKey::class, $ssh_key);
			foreach ($data[$ssh_key->getResponseListName()] as $object)
			{
				if ($object['id'] !== $ssh_key->getId()) continue;
				foreach ($ssh_key->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
				break;
			}
		}

		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->getSSHKeys();
	}

	public function testUpdateSSHKey()
	{
		$ssh_id = '3b8066a7-b438-455a-9688-44afc9a3597f';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ssh_key = new SSHKey();
		$ssh_key->setId($ssh_id);
		$ssh_key->setName('winning');
		$ssh_key->setSshKey('ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQDDoVaVB2xla/dEVuYyGr0K+wrj59q+/ZZAY3t6R+kKltg4kWgg3Lf8qRysQkQK80wWhNDjQ5IoSYXtokjadYU2n+WChyafzsTTPwRPxXE6ujIs+QMOd9FrWn8AdAvfAU3pMeaOk8JsM97ktBXGPzBPJnS2TAxrMxudIkEoiVgATYHDLjU1S1jiXrcQ16nxm9tRMmBneLq6ENsgFLymZTxDO/TkqGbZdxTY3J+whg6rSKgCpfOUI08kau5+yYsRnG5Ys3zP04PQbUTPeVpTm9PGMX8Yrxv0o673dUYQqe/SHrMD5seLBuWAACy02/dfsk7nL+z62nwk1X8W8ySzIhIvR1c47jNXdJhZdik/IzZGPlDEDgD+5yiNxhhiMHzvBIyf25SrHw4hUTSZW2aQMLK/5aBUWMnDTzxXjcJPSd2UhevGJqfcCGCRe2TS8Jg6ytps1npIiACLeW+JgNDhCWtD98eAdGEvnD7YktJ188aDVlsfbmUVHuDnWpx9y5dSASpXvd2fup8Rwbc2BvSNtLISD8P3wP0nwUlogIIp54JuUI1zQiqXjSQUYBHIfSoMfJHKOVsY8X21wSpwfXK12DfJINDsDShj52qwnNBVG5v+DT9UdnpgdfOnNBAKPl9MGDth3GNyxSJw70+IhndUIO7pN8qDJu2tYvX6kl+VXl87DQ== phpunit@vultr');

		$client->ssh_keys->updateSSHKey($ssh_key);

		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->updateSSHKey($ssh_key);
	}

	public function testDeleteSSHKey()
	{
		$ssh_id = '3b8066a7-b438-455a-9688-44afc9a3597f';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->ssh_keys->deleteSSHKey($ssh_id);

		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->deleteSSHKey($ssh_id);
	}

	public function testCreateSSHKey()
	{
		$ssh_key = new SSHKey();
		$ssh_key->setName('winning');
		$ssh_key->setSshKey('ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQDDoVaVB2xla/dEVuYyGr0K+wrj59q+/ZZAY3t6R+kKltg4kWgg3Lf8qRysQkQK80wWhNDjQ5IoSYXtokjadYU2n+WChyafzsTTPwRPxXE6ujIs+QMOd9FrWn8AdAvfAU3pMeaOk8JsM97ktBXGPzBPJnS2TAxrMxudIkEoiVgATYHDLjU1S1jiXrcQ16nxm9tRMmBneLq6ENsgFLymZTxDO/TkqGbZdxTY3J+whg6rSKgCpfOUI08kau5+yYsRnG5Ys3zP04PQbUTPeVpTm9PGMX8Yrxv0o673dUYQqe/SHrMD5seLBuWAACy02/dfsk7nL+z62nwk1X8W8ySzIhIvR1c47jNXdJhZdik/IzZGPlDEDgD+5yiNxhhiMHzvBIyf25SrHw4hUTSZW2aQMLK/5aBUWMnDTzxXjcJPSd2UhevGJqfcCGCRe2TS8Jg6ytps1npIiACLeW+JgNDhCWtD98eAdGEvnD7YktJ188aDVlsfbmUVHuDnWpx9y5dSASpXvd2fup8Rwbc2BvSNtLISD8P3wP0nwUlogIIp54JuUI1zQiqXjSQUYBHIfSoMfJHKOVsY8X21wSpwfXK12DfJINDsDShj52qwnNBVG5v+DT9UdnpgdfOnNBAKPl9MGDth3GNyxSJw70+IhndUIO7pN8qDJu2tYvX6kl+VXl87DQ== phpunit@vultr');

		$data = $ssh_key->toArray();
		$data['id'] = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data['date_created'] = '2020-10-10T01:56:20+00:00';

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode([$ssh_key->getResponseName() => $data])),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ssh_key = $client->ssh_keys->createSSHKey($ssh_key->getName(), $ssh_key->getSshKey());
		$this->assertInstanceOf(SSHKey::class, $ssh_key);
		$array = $ssh_key->toArray();
		foreach ($ssh_key->toArray() as $prop => $prop_val)
		{
			$this->assertEquals($prop_val, $data[$prop]);
		}
		$this->expectException(SSHKeyException::class);
		$client->ssh_keys->createSSHKey($ssh_key->getName(), $ssh_key->getSshKey());
	}
}
