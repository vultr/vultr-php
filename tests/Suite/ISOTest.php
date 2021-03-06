<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\ISO\ISO;
use Vultr\VultrPhp\Services\ISO\ISOException;
use Vultr\VultrPhp\Services\ISO\PublicISO;
use Vultr\VultrPhp\Tests\VultrTest;

class ISOTest extends VultrTest
{
	public function testGetISO()
	{
		$id = 'cb676a46-66fd-4dfb-b839-12312414';
		$data = $this->getDataProvider()->getData($id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(404, [], json_encode(['error' => 'Not found'])),
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
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(404, [], json_encode(['error' => 'Not found'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new ISO(), $client->iso->getISOs($options), $data);

		$this->expectException(ISOException::class);
		$client->iso->getISOs();
	}

	public function testGetPublicISOs()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new PublicISO(), $client->iso->getPublicISOs($options), $data);

		$this->expectException(ISOException::class);
		$client->iso->getPublicISOs();
	}

	public function testCreateISO()
	{
		$data = $this->getDataProvider()->getData();
		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$iso = $client->iso->createISO('http://myamazingiso.com');
		$this->assertInstanceOf(ISO::class, $iso);
		$array = $iso->toArray();
		foreach ($data[$iso->getResponseName()] as $prop => $prop_val)
		{
			$this->assertEquals($prop_val, $array[$prop]);
		}

		$this->expectException(ISOException::class);
		$client->iso->createISO('hafsadfsdgfsadg');
	}

	public function testDeleteISO()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->iso->deleteISO($id);

		$this->expectException(ISOException::class);
		$client->iso->deleteISO($id);
	}
}
