<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Snapshots\Snapshot;
use Vultr\VultrPhp\Services\Snapshots\SnapshotException;
use Vultr\VultrPhp\Tests\VultrTest;

class SnapshotsTest extends VultrTest
{
	public function testGetSnapshots()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new Snapshot(), $client->snapshots->getSnapshots(), $data);

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshots();
	}

	public function testGetSnapshotsFilter()
	{
		$description = 'Example Snapshot';
		$data = $this->getDataProvider()->getData($description);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new Snapshot(), $client->snapshots->getSnapshots($description), $data);

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshots($description);
	}

	public function testGetSnapshot()
	{
		$id = 'cb676a46-66fd-4dfb-b839-443f2e141410';
		$data = $this->getDataProvider()->getData($id);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$snapshot = $client->snapshots->getSnapshot($id);
		$this->assertInstanceOf(Snapshot::class, $snapshot);
		foreach ($snapshot->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$snapshot->getResponseName()][$attr]);
		}

		$this->expectException(SnapshotException::class);
		$client->snapshots->getSnapshot($id);
	}

	public function testDeleteSnapshot()
	{
		$id = 'asfsdgsdaf-sadgf-sadga-sdgasdg';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->snapshots->deleteSnapshot($id);

		$this->expectException(SnapshotException::class);
		$client->snapshots->deleteSnapshot($id);
	}

	public function testCreateSnapshot()
	{
		$id = 'asfsdgsdaf-sadgf-sadga-sdgasdg';
		$description = 'Example Snapshot';
		$data = $this->getDataProvider()->getData($id);
		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$snapshot = $client->snapshots->createSnapshot($id, $description);
		$this->assertInstanceOf(Snapshot::class, $snapshot);
		foreach ($snapshot->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$snapshot->getResponseName()][$attr]);
		}
		$this->assertEquals($id, $snapshot->getId());
		$this->assertEquals($description, $snapshot->getDescription());

		$this->expectException(SnapshotException::class);
		$client->snapshots->createSnapshot($id, $description);
	}

	public function testCreateSnapshotFromURL()
	{
		$url = 'https://www.vultr.com/your-awesome-diskimage.raw';
		$description = 'Example Snapshot';
		$data = $this->getDataProvider()->getData();
		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$snapshot = $client->snapshots->createSnapshotFromURL($url, $description);
		$this->assertInstanceOf(Snapshot::class, $snapshot);
		foreach ($snapshot->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$snapshot->getResponseName()][$attr]);
		}
		$this->assertEquals($description, $snapshot->getDescription());

		$this->expectException(SnapshotException::class);
		$client->snapshots->createSnapshotFromURL($url, $description);
	}
}
