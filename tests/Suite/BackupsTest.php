<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Backups\Backup;
use Vultr\VultrPhp\Services\Backups\BackupException;
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
		$this->testListObject(new Backup(), $backups, $data);

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
		$this->testListObject(new Backup(), $backups, $data);

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
