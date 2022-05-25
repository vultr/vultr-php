<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Backups\Backup;
use Vultr\VultrPhp\Services\Backups\BackupException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

use Vultr\VultrPhp\Tests\VultrTest;

class BackupsTest extends VultrTest
{
	public function testGetBackups()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$backups = $client->backups->getBackups();
		$this->assertEquals($data['meta']['total'], count($backups));
		foreach ($backups as $backup)
		{
			$this->assertInstanceOf(Backup::class, $backup);
			foreach ($data[$backup->getResponseListName()] as $object)
			{
				if ($object['id'] !== $backup->getId()) continue;

				foreach ($backup->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
				break;
			}
		}

		$this->expectException(BackupException::class);
		$client->backups->getBackups();
	}

	public function testGetBackupsByInstanceId()
	{
		$id = 'cb676a46-66fd-4dfb-b839-1141515';
		$data = $this->getDataProvider()->getData($id);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request']))
		]);

		$backups = $client->backups->getBackups($id);
		$this->assertEquals($data['meta']['total'], count($backups));
		foreach ($backups as $backup)
		{
			$this->assertInstanceOf(Backup::class, $backup);
			foreach ($data[$backup->getResponseListName()] as $object)
			{
				if ($object['id'] !== $backup->getId()) continue;

				foreach ($backup->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
				break;
			}
		}

		$this->expectException(BackupException::class);
		$client->backups->getBackups($id);
	}

	public function testGetBackup()
	{
		$id = 'cb676a46-66fd-4dfb-b839-12312414';
		$data = $this->getDataProvider()->getData($id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(404, [], json_encode(['error' => 'Not found'])),
		]);

		$backup = $client->backups->getBackup($id);
		$this->assertInstanceOf(Backup::class, $backup);
		foreach ($backup->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$backup->getResponseName()][$attr]);
		}

		$this->expectException(BackupException::class);
		$client->backups->getBackup('wrong-id');
	}
}
