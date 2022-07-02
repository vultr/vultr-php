<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\DNS\DNSException;
use Vultr\VultrPhp\Services\DNS\DNSSOA;
use Vultr\VultrPhp\Services\DNS\Domain;
use Vultr\VultrPhp\Services\DNS\Record;
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

		$options = $this->createListOptions();
		$this->testListObject(new Domain(), $client->dns->getDomains($options), $data, 'domain', 'getDomain');

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

		$this->testGetObject(new Domain(), $client->dns->createDomain($data['domain']['domain'], $data['domain']['dns_sec'], '127.0.0.1'), $data);

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

	public function testGetRecords()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new Record(), $client->dns->getRecords('example.com', $options), $data);

		$this->expectException(DNSException::class);
		$client->dns->getRecords('example.com');
	}

	public function testGetRecord()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testGetObject(new Record(), $client->dns->getRecord('example.com', 'cb676a46-66fd-4dfb-b839-443f2e6c0b60'), $data);

		$this->expectException(DNSException::class);
		$client->dns->getRecord('example.com', 'cb676a46-66fd-4dfb-b839-443f2e6c0b60');
	}

	public function testCreateRecord()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$post_record = new Record();
		$post_record->setName($data['record']['name']);
		$post_record->setType($data['record']['type']);
		$post_record->setData($data['record']['data']);
		$record = $client->dns->createRecord('example.com', $post_record);

		$this->assertEquals($post_record->getName(), $record->getName());
		$this->assertEquals($post_record->getType(), $record->getType());
		$this->assertEquals($post_record->getType(), $record->getType());
		$this->testGetObject(new Record(), $record, $data);

		$this->expectException(DNSException::class);
		$client->dns->createRecord('example.com', $post_record);
	}

	public function testUpdateRecord()
	{
		$provider = $this->getDataProvider();
		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$record = new Record();
		$record->setId('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$record->setName('foo');
		$record->setTtl(100);

		$client->dns->updateRecord('example.com', $record);

		$this->expectException(DNSException::class);
		$client->dns->updateRecord('example.com', $record);
	}

	public function testDeleteRecord()
	{
		$provider = $this->getDataProvider();
		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->dns->deleteRecord('example.com', 'cb676a46-66fd-4dfb-b839-443f2e6c0b60');

		$this->expectException(DNSException::class);
		$client->dns->deleteRecord('example.com', 'cb676a46-66fd-4dfb-b839-443f2e6c0b60');
	}

	public function testGetSOAInfo()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testGetObject(new DNSSOA(), $client->dns->getSOAInfo('example.com'), $data);

		$this->expectException(DNSException::class);
		$client->dns->getSOAInfo('example.com');
	}

	public function testUpdateSOAInfo()
	{
		$provider = $this->getDataProvider();
		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$soa = new DNSSOA();
		$soa->setEmail('tos@example.com');

		$client->dns->updateSOAInfo('example.com', $soa);

		$this->expectException(DNSException::class);
		$client->dns->updateSOAInfo('example.com', $soa);
	}
}
