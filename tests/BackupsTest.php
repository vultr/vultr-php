<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Backups\Backup;
use Vultr\VultrPhp\Services\Backups\BackupException;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class BackupsTest extends TestCase
{
	public function testGetBackups()
	{
		$data = [
			'backups' => [
				[
					'id' => 'cb676a46-66fd-4dfb-b839-12312414',
					'date_created' => '2020-10-10T01:56:20+00:00',
					'description' => 'Example automatic backup',
					'size' => 5000000,
					'status' => 'complete'
				],
				[
					'id' => 'cb676a46-66fd-4dfb-b839-32525262',
					'date_created' => '2020-10-10T01:56:20+00:00',
					'description' => 'Example automatic backup',
					'size' => 0,
					'status' => 'pending'
				],
			],
			'meta' => [
				'total' => 2,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'backups'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		foreach ($client->backups->getBackups() as $backup)
		{
			$this->assertInstanceOf(Backup::class, $backup);
			// I don't care about optimizations. Array is small anyways.
			foreach ($data['backups'] as $object)
			{
				if ($object['id'] !== $backup->getId()) continue;

				foreach ($backup->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
			}
		}

		$this->expectException(BackupException::class);
		$client->backups->getBackups();
	}

	public function testGetBackup()
	{
		$id = 'cb676a46-66fd-4dfb-b839-12312414';
		$data = [
			'backup' => [
				'id' => $id,
				'date_created' => '2020-10-10T01:56:20+00:00',
				'description' => 'Example automatic backup',
				'size' => 5000000,
				'status' => 'complete'
			]
		];

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Not Found', new Request('GET', 'backups/wrong-id'), new Response(404, [], json_encode(['error' => 'Not found']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		$backup = $client->backups->getBackup($id);
		$this->assertInstanceOf(Backup::class, $backup);
		foreach ($backup->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['backup'][$attr]);
		}

		$this->expectException(BackupException::class);
		$client->backups->getBackup('wrong-id');
	}
}
