<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\ISO\ISOException;
use Vultr\VultrPhp\Services\ISO\ISO;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class ISOTest extends VultrTest
{
	public function testGetISO()
	{
		$id = 'cb676a46-66fd-4dfb-b839-12312414';
		$data = $this->getDataProvider()->getData($id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Not Found', new Request('GET', 'iso/hello-world'), new Response(404, [], json_encode(['error' => 'Not found']))),
		]);

		$iso = $client->iso->getISO($id);
		$this->assertInstanceOf(ISO::class, $iso);
		foreach ($iso->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$iso->getResponseName()][$attr]);
		}

		$this->expectException(ISOException::class);
		$client->iso->getISO('hello-world');
	}

	public function testGetISOs()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testGetPublicISOs()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testCreateISO()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteISO()
	{
		$this->markTestSkipped('Incomplete');
	}
}
