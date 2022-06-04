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
}
