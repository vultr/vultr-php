<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\ReservedIPs\ReservedIPException;
use Vultr\VultrPhp\Services\ReservedIPs\ReservedIP;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class ReservedIPsTest extends VultrTest
{
	public function testGetReservedIP()
	{
		$reserved_id = '3b8066a7-b438-455a-9688-44afc9a3597f';
		$data = $this->getDataProvider()->getData($reserved_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'reserved-ips/'.$reserved_id), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$reserved_ip = $client->reserved_ips->getReservedIP($reserved_id);
		foreach ($reserved_ip->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$reserved_ip->getResponseName()][$attr]);
		}

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->getReservedIP($reserved_id);
	}

	public function testGetReservedIPs()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'reserved-ips'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		foreach ($client->reserved_ips->getReservedIPs() as $reserved_ip)
		{
			$this->assertInstanceOf(ReservedIP::class, $reserved_ip);
			foreach ($data[$reserved_ip->getResponseListName()] as $object)
			{
				if ($object['id'] !== $reserved_ip->getId()) continue;
				foreach ($reserved_ip->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
			}
		}

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->getReservedIPs();
	}

	public function testDeleteReservedIP()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testCreateReservedIP()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testConvertInstanceIP()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testAttachReservedIP()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDetachReservedIP()
	{
		$this->markTestSkipped('Incomplete');
	}
}
