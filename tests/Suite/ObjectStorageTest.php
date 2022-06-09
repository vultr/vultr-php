<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\ObjectStorage\ObjectStorage;
use Vultr\VultrPhp\Services\ObjectStorage\ObjectStorageException;
use Vultr\VultrPhp\Services\ObjectStorage\ObjStoreCluster;
use Vultr\VultrPhp\Tests\VultrTest;

class ObjectStorageTest extends VultrTest
{
	public function testGetObjectStoreSubs()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
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

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new ObjectStorage(), $client->objectstorage->getObjectStoreSub($id), $data);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->getObjectStoreSub($id);
	}

	public function testCreateObjectStoreSub()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$object = $client->objectstorage->createObjectStoreSub($data['object_storage']['cluster_id'], $data['object_storage']['label']);
		$this->assertEquals($object->getClusterId(), $data['object_storage']['cluster_id']);
		$this->assertEquals($object->getLabel(), $data['object_storage']['label']);
		$this->testGetObject(new ObjectStorage(), $object, $data);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->createObjectStoreSub($data['object_storage']['cluster_id'], $data['object_storage']['label']);
	}

	public function testDeleteObjectStoreSub()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->objectstorage->deleteObjectStoreSub($id);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->deleteObjectStoreSub($id);
	}

	public function testUpdateObjectStoreSub()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$obj = new ObjectStorage();
		$obj->setId('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$obj->setLabel('Hello darkness my old friend');

		$client->objectstorage->updateObjectStoreSub($obj);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->updateObjectStoreSub($obj);
	}

	public function testRegenObjectStoreKeys()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$object_data = $provider->getObjectStoreSub();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($object_data)),
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$object = $client->objectstorage->getObjectStoreSub('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$this->testGetObject(new ObjectStorage(), $object, $object_data);

		$new_obj = $client->objectstorage->regenObjectStoreKeys($object);
		$this->assertEquals($object->getId(), $new_obj->getId());
		$this->assertEquals($object->getStatus(), $new_obj->getStatus());
		$this->assertEquals($object->getRegion(), $new_obj->getRegion());
		$this->assertEquals($object->getClusterId(), $new_obj->getClusterId());
		$this->assertEquals($object->getDateCreated(), $new_obj->getDateCreated());
		$this->assertEquals($object->getS3Hostname(), $new_obj->getS3Hostname());
		$this->assertNotEquals($object->getS3AccessKey(), $new_obj->getS3AccessKey());
		$this->assertNotEquals($object->getS3SecretKey(), $new_obj->getS3SecretKey());

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->regenObjectStoreKeys($object);
	}

	public function testGetClusters()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new ObjStoreCluster(), $client->objectstorage->getClusters(), $data);

		$this->expectException(ObjectStorageException::class);
		$client->objectstorage->getClusters();
	}
}
