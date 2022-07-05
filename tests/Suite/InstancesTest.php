<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Instances\Instance;
use Vultr\VultrPhp\Services\Instances\InstanceException;
use Vultr\VultrPhp\Services\Instances\InstanceService;
use Vultr\VultrPhp\Services\Instances\IsoStatus;
use Vultr\VultrPhp\Services\Instances\VPCAttachment;
use Vultr\VultrPhp\Tests\VultrTest;

class InstancesTest extends VultrTest
{
	public function testGetInstance()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new Instance(), $client->instances->getInstance($id), $data);

		$this->expectException(InstanceException::class);
		$client->instances->getInstance($id);
	}

	public function testGetInstances()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new Instance(), $client->instances->getInstances([
			InstanceService::FILTER_LABEL   => 'hello_world',
			InstanceService::FILTER_MAIN_IP => '192.168.0.1',
			InstanceService::FILTER_REGION  => 'ewr'
		], $options), $data);

		$this->expectException(InstanceException::class);
		$client->instances->getInstances(null, $options);
	}

	public function testCreateInstance()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testUpdateInstance()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteInstance()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->instances->deleteInstance($id));

		$this->expectException(InstanceException::class);
		$client->instances->deleteInstance($id);
	}

	public function testHaltInstance()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->instances->haltInstance($id));

		$this->expectException(InstanceException::class);
		$client->instances->haltInstance($id);
	}

	public function testHaltInstances()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = [
			"cb676a46-66fd-4dfb-b839-443f2e6c0b60",
			"1d651bd2-b93c-4bb6-8b91-0546fd765f15",
			"c2790719-278d-474c-8dff-cb35d6e5503f"
		];

		$this->assertNull($client->instances->haltInstances($ids));

		$this->expectException(InstanceException::class);
		$client->instances->haltInstances($ids);
	}

	public function testRebootInstance()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->instances->rebootInstance($id));

		$this->expectException(InstanceException::class);
		$client->instances->rebootInstance($id);
	}

	public function testRebootInstances()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = [
			"cb676a46-66fd-4dfb-b839-443f2e6c0b60",
			"1d651bd2-b93c-4bb6-8b91-0546fd765f15",
			"c2790719-278d-474c-8dff-cb35d6e5503f"
		];

		$this->assertNull($client->instances->rebootInstances($ids));

		$this->expectException(InstanceException::class);
		$client->instances->rebootInstances($ids);
	}

	public function testStartInstances()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = [
			"cb676a46-66fd-4dfb-b839-443f2e6c0b60",
			"1d651bd2-b93c-4bb6-8b91-0546fd765f15",
			"c2790719-278d-474c-8dff-cb35d6e5503f"
		];

		$this->assertNull($client->instances->startInstances($ids));

		$this->expectException(InstanceException::class);
		$client->instances->startInstances($ids);
	}

	public function testStartInstance()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->instances->startInstance($id));

		$this->expectException(InstanceException::class);
		$client->instances->startInstance($id);
	}

	public function testReinstallInstance()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$hostname = $data['instance']['hostname'];
		$this->testGetObject(new Instance(), $client->instances->reinstallInstance($id, $hostname), $data);

		$this->expectException(InstanceException::class);
		$client->instances->reinstallInstance($id, $hostname);
	}

	public function testGetBandwidth()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';

		$bandwidth = $client->instances->getBandwidth($id);
		foreach ($data['bandwidth'] as $date => $attributes)
		{
			$this->assertEquals($bandwidth[$date]['incoming_bytes'], $attributes['incoming_bytes']);
			$this->assertEquals($bandwidth[$date]['outgoing_bytes'], $attributes['outgoing_bytes']);
		}

		$this->expectException(InstanceException::class);
		$client->instances->getBandwidth($id);
	}

	public function testGetNeighbors()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertEquals($data['neighbors'], $client->instances->getNeighbors($id));

		$this->expectException(InstanceException::class);
		$client->instances->getNeighbors($id);
	}

	public function testRestoreInstance()
	{
		$provider = $this->getDataProvider();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($provider->getData())),
			new Response(202, ['Content-Type' => 'application/json'], json_encode($provider->getData())),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b835-443f2e6c0b60';
		$backup_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$snapshot_id = 'cb676a46-66fd-4dfb-b831-443f2e6c0b60';

		try
		{
			$client->instances->restoreInstance($id, null, null);
		}
		catch (InstanceException $e)
		{
			$this->assertStringContainsString('1 of the following parameters must be specified', $e->getMessage());
		}

		try
		{
			$client->instances->restoreInstance($id, $snapshot_id, $backup_id);
		}
		catch (InstanceException $e)
		{
			$this->assertStringContainsString('Only 1 parameter is allowed to be specified', $e->getMessage());
		}

		$this->assertNull($client->instances->restoreInstance($id, $snapshot_id));
		$this->assertNull($client->instances->restoreInstance($id, null, $backup_id));

		$this->expectException(InstanceException::class);
		$client->instances->restoreInstance($id, $snapshot_id);
	}

	public function testGetVPCs()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$id = 'cb676a46-66fd-4dfb-b835-443f2e6c0b60';
		$this->testListObject(new VPCAttachment(), $client->instances->getVPCs($id, $options), $data);

		$this->expectException(InstanceException::class);
		$client->instances->getVPCs($id, $options);
	}

	public function testAttachVPC()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$vpc_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b65';
		$this->assertNull($client->instances->attachVPC($id, $vpc_id));

		$this->expectException(InstanceException::class);
		$client->instances->attachVPC($id, $vpc_id);
	}

	public function testDetachVPC()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$vpc_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b65';
		$this->assertNull($client->instances->detachVPC($id, $vpc_id));

		$this->expectException(InstanceException::class);
		$client->instances->detachVPC($id, $vpc_id);
	}

	public function testGetIsoStatus()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new IsoStatus(), $client->instances->getIsoStatus($id), $data);

		$this->expectException(InstanceException::class);
		$client->instances->getIsoStatus($id);
	}

	public function testAttachIsoToInstance()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$iso_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new IsoStatus(), $client->instances->attachIsoToInstance($id, $iso_id), $data);

		$this->expectException(InstanceException::class);
		$client->instances->attachIsoToInstance($id, $iso_id);
	}

	public function testDetachIsoFromInstance()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$status = $client->instances->detachIsoFromInstance($id);
		$this->testGetObject(new IsoStatus(), $status, $data);

		$this->assertNull($status->getIsoId());

		$this->expectException(InstanceException::class);
		$client->instances->detachIsoFromInstance($id);
	}
}
