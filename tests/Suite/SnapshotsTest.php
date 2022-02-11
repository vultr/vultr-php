<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Snapshots\Snapshot;
use Vultr\VultrPhp\Services\Snapshots\SnapshotException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class SnapshotsTest extends VultrTest
{
	public function testGetSnapshots()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'snapshots'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->snapshots->getSnapshots() as $snapshot)
		{
			$this->assertInstanceOf(Snapshot::class, $snapshot);
			foreach ($data['snapshots'] as $object)
			{
				if ($object['id'] !== $snapshot->getId()) continue;
				foreach ($snapshot->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
			}
		}

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshots();
	}

	public function testGetSnapshotsFilter()
	{
		$description = 'Example Snapshot';
		$data = $this->getDataProvider()->getData($description);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'snapshots'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->snapshots->getSnapshots($description) as $snapshot)
		{
			$this->assertInstanceOf(Snapshot::class, $snapshot);
			foreach ($data['snapshots'] as $object)
			{
				if ($object['id'] !== $snapshot->getId()) continue;
				foreach ($snapshot->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
			}
		}

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshots($description);
	}

	public function testGetSnapshot()
	{
		$id = 'cb676a46-66fd-4dfb-b839-443f2e141410';
		$data = $this->getDataProvider()->getData($id);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'snapshots/'.$id), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$snapshot = $client->snapshots->getSnapshot($id);
		foreach ($snapshot->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['snapshot'][$attr]);
		}

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshot($id);
	}
}
