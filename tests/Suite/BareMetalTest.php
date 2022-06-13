<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\BareMetal\BareMetal;
use Vultr\VultrPhp\Services\BareMetal\BareMetalException;
use Vultr\VultrPhp\Tests\VultrTest;

class BareMetalTest extends VultrTest
{
	public function testGetBareMetals()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new BareMetal(), $client->baremetal->getBareMetals(), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getBareMetals();
	}

	public function testGetBareMetal()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new BareMetal(), $client->baremetal->getBareMetal($id), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getBareMetal($id);
	}

	public function testDeleteBareMetal()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->baremetal->deleteBareMetal($id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->deleteBareMetal($id);
	}
}
