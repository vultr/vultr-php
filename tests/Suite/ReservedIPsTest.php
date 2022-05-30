<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\Services\ReservedIPs\ReservedIPException;
use Vultr\VultrPhp\Services\ReservedIPs\ReservedIP;

use GuzzleHttp\Psr7\Response;

use Vultr\VultrPhp\Tests\VultrTest;

class ReservedIPsTest extends VultrTest
{
	public function testGetReservedIP()
	{
		$reserved_id = '3b8066a7-b438-455a-9688-44afc9a3597f';
		$data = $this->getDataProvider()->getData($reserved_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
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
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
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
				break;
			}
		}

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->getReservedIPs();
	}

	public function testDeleteReservedIP()
	{
		$id = 'asfsdgsdaf-sadgf-sadga-sdgasdg';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->reserved_ips->deleteReservedIP($id);

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->deleteReservedIP($id);
	}

	public function testCreateReservedIP()
	{
		$data = $this->getDataProvider()->getData();
		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$region = 'ewr';
		$ip_type = 'v4';
		$label = 'Example Reserved IPv4';
		$reserved_ip = $client->reserved_ips->createReservedIP($region, $ip_type, $label);
		$this->assertInstanceOf(ReservedIP::class, $reserved_ip);
		foreach ($reserved_ip->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data[$reserved_ip->getResponseName()][$attr]);
		}
		$this->assertEquals($region, $reserved_ip->getRegion());
		$this->assertEquals($ip_type, $reserved_ip->getIpType());
		$this->assertEquals($label, $reserved_ip->getLabel());

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->createReservedIP($region, $ip_type, $label);
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
