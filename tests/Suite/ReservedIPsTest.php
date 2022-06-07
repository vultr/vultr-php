<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\ReservedIPs\ReservedIP;
use Vultr\VultrPhp\Services\ReservedIPs\ReservedIPException;
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

		$this->testGetObject(new ReservedIP(), $client->reserved_ips->getReservedIP($reserved_id), $data);

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

		$this->testListObject(new ReservedIP(), $client->reserved_ips->getReservedIPs(), $data);

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
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testGetObject(new ReservedIP(), $client->reserved_ips->convertInstanceIP($data['reserved_ip']['subnet'], $data['reserved_ip']['label']), $data);

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->convertInstanceIP($data['reserved_ip']['subnet'], $data['reserved_ip']['label']);
	}

	public function testAttachReservedIP()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ip = '192.168.0.1';
		$instance_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->reserved_ips->attachReservedIP($ip, $instance_id);

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->attachReservedIP($ip, $instance_id);
	}

	public function testDetachReservedIP()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ip = '192.168.0.1';
		$client->reserved_ips->detachReservedIP($ip);

		$this->expectException(ReservedIPException::class);
		$client->reserved_ips->detachReservedIP($ip);
	}
}
