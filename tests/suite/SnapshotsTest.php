<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Snapshots\Snapshot;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class SnapshotsTest extends VultrTest
{
	public function testGetSnapshots()
	{
		$data = [
			'snapshots' => [
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e141410',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 215,
					'app_id'          => 0
				],
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e6c0b60',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot2',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 0,
					'app_id'          => 25
				],
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f214d10b604',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'snapshots go brrrr',
					'size'            => 0,
					'compressed_size' => 0,
					'status'          => 'pending',
					'os_id'           => 0,
					'app_id'          => 0
				],
			],
			'meta' => [
				'total' => 3,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];

		$data2 = $data;
		unset($data['snapshots'][2]);
		$data2['meta']['total'] = 2;
		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data2)),
			new RequestException('Bad Request', new Request('GET', 'snapshots'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		foreach ([
			$client->snapshots->getSnapshots(),
			$client->snapshots->getSnapshots('Example Snapshot')
		] as $snapshots)
		{
			foreach ($snapshots as $snapshot)
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
		}
	}

	public function testGetSnapshotsFilter()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testGetSnapshot()
	{
		$this->markTestIncomplete('Not implemented');
		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data2)),
			new RequestException('Bad Request', new Request('GET', 'backups'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);
	}
}
