<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Instances\Instance;
use Vultr\VultrPhp\Services\Instances\InstanceException;
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
		$this->markTestSkipped('Incomplete');
	}
}
