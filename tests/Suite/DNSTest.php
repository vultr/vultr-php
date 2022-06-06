<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\DNS\DNSException;
use Vultr\VultrPhp\Services\DNS\Domain;
use Vultr\VultrPhp\Tests\VultrTest;

class DNSTest extends VultrTest
{
	public function testGetDomains()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new Domain(), $client->dns->getDomains(), $data, 'domain', 'getDomain');

		$this->expectException(DNSException::class);
		$client->dns->getDomains();
	}

	public function testGetDomain()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testGetObject(new Domain(), $client->dns->getDomain('example.com'), $data);

		$this->expectException(DNSException::class);
		$client->dns->getDomain('example.com');
	}

	public function testCreateDomain()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testGetObject(new Domain(), $client->dns->createDomain($data['domain']['domain'], $data['domain']['dns_sec']), $data);

		$this->expectException(DNSException::class);
		$client->dns->createDomain($data['domain']['domain'], $data['domain']['dns_sec']);
	}

	public function testDeleteDomain()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->dns->deleteDomain('example.com');

		$this->expectException(DNSException::class);
		$client->dns->deleteDomain('example.com');
	}

	public function testUpdateDomain()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$domain = new Domain();
		$domain->setDomain('example.com');
		$domain->setDnsSec('disabled');

		$client->dns->updateDomain($domain);

		$this->expectException(DNSException::class);
		$client->dns->updateDomain($domain);
	}

	public function testGetDNSSecInfo()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$response_data = $client->dns->getDNSSecInfo('example.com');
		$this->assertIsArray($response_data);
		$this->assertEquals(count($response_data), count($data['dns_sec']));
		foreach ($response_data as $index => $entry)
		{
			$this->assertEquals($entry, $data['dns_sec'][$index]);
		}

		$this->expectException(DNSException::class);
		$client->dns->getDNSSecInfo('example.com');
	}
}
