<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\VPC\VirtualPrivateCloud;
use Vultr\VultrPhp\Services\VPC\VPCException;
use Vultr\VultrPhp\Tests\VultrTest;

class VPCTest extends VultrTest
{
	public function testGetVPC()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new VirtualPrivateCloud(), $client->vpc->getVPC($id), $data);

		$this->expectException(VPCException::class);
		$client->vpc->getVPC($id);
	}

	public function testGetVPCs()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new VirtualPrivateCloud(), $client->vpc->getVPCs(), $data);

		$this->expectException(VPCException::class);
		$client->vpc->getVPCs();
	}

	public function testCreateVPC()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$vpc = new VirtualPrivateCloud();
		$vpc->setRegion($data['vpc']['region']);
		$vpc->setDescription($data['vpc']['description']);
		$vpc->setV4Subnet($data['vpc']['v4_subnet']);
		$vpc->setV4SubnetMask($data['vpc']['v4_subnet_mask']);
		$this->testGetObject(new VirtualPrivateCloud(), $client->vpc->createVPC($vpc), $data);

		$this->expectException(VPCException::class);
		$client->vpc->createVPC($vpc);
	}

	public function testUpdateVPC()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$vpc = new VirtualPrivateCloud();
		$vpc->setId('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$vpc->setDescription('hello world');
		$client->vpc->updateVPC($vpc);

		$this->expectException(VPCException::class);
		$client->vpc->updateVPC($vpc);
	}

	public function testDeleteVPC()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->vpc->deleteVPC($id);

		$this->expectException(VPCException::class);
		$client->vpc->deleteVPC($id);
	}
}
