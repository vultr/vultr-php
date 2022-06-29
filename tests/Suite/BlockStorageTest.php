<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\BlockStorage\BlockStorage;
use Vultr\VultrPhp\Services\BlockStorage\BlockStorageException;
use Vultr\VultrPhp\Tests\VultrTest;

class BlockStorageTest extends VultrTest
{
	public function testGetBlockDevices()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new BlockStorage(), $client->blockstorage->getBlockDevices($options), $data);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->getBlockDevices();
	}

	public function testGetBlockDevice()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new BlockStorage(), $client->blockstorage->getBlockDevice($id), $data);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->getBlockDevice($id);
	}

	public function testCreateBlockDevice()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$block = new BlockStorage();
		$block->setRegion($data['block']['region']);
		$block->setSizeGb($data['block']['size_gb']);
		$block->setLabel($data['block']['label']);
		$block->setBlockType($data['block']['block_type']);

		$this->testGetObject(new BlockStorage(), $client->blockstorage->createBlockDevice($block), $data);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->createBlockDevice($block);
	}

	public function testDeleteBlockDevice()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->blockstorage->deleteBlockDevice($id);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->deleteBlockDevice($id);
	}

	public function testUpdateBlockDevice()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$block = new BlockStorage();
		$block->setId('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$block->setLabel('hello world');
		$block->setSizeGb(400);

		$client->blockstorage->updateBlockDevice($block);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->updateBlockDevice($block);
	}

	public function testAttachBlockDevice()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->blockstorage->attachBlockDevice('cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-66fd-4dfb-b839-cb676a46asd14', true);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->attachBlockDevice('cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-66fd-4dfb-b839-cb676a46asd14', true);
	}

	public function testDetachBlockDevice()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->blockstorage->detachBlockDevice('cb676a46-66fd-4dfb-b839-443f2e6c0b60', true);

		$this->expectException(BlockStorageException::class);
		$client->blockstorage->detachBlockDevice('cb676a46-66fd-4dfb-b839-443f2e6c0b60', true);
	}
}
