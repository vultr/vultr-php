<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\ObjectStorage\ObjectStorage;
use Vultr\VultrPhp\Services\ObjectStorage\ObjectStorageException;
use Vultr\VultrPhp\Tests\VultrTest;

class ObjectStorageTest extends VultrTest
{
	public function testGetObjectStoreSubs()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new ObjectStorage(), $client->objectstorage->getObjectStoreSubs(), $data);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->getObjectStoreSubs();
	}

	public function testGetObjectStoreSub()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new ObjectStorage(), $client->objectstorage->getObjectStoreSub($id), $data);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->getObjectStoreSub($id);
	}
}
