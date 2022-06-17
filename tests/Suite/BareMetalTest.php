<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\BareMetal\BareMetal;
use Vultr\VultrPhp\Services\BareMetal\BareMetalCreate;
use Vultr\VultrPhp\Services\BareMetal\BareMetalException;
use Vultr\VultrPhp\Services\BareMetal\BareMetalIPv4Info;
use Vultr\VultrPhp\Services\BareMetal\BareMetalIPv6Info;
use Vultr\VultrPhp\Services\BareMetal\BareMetalUpdate;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\Util\ModelInterface;

class BareMetalTest extends VultrTest
{
	public function testGetBareMetals()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new BareMetal(), $client->baremetal->getBareMetals(), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getBareMetals();
	}

	public function testGetBareMetal()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new BareMetal(), $client->baremetal->getBareMetal($id), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getBareMetal($id);
	}

	public function testCreateBareMetal()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$region = 'ams';
		$plan = 'vbm-4c-32gb';
		$app_id = 3;
		$create = new BareMetalCreate($region, $plan);
		$create->setAppId($app_id);

		$this->assertEquals($app_id, $create->getAppId());
		$this->assertEquals($region, $create->getRegion());
		$this->assertEquals($plan, $create->getPlan());

		$this->testGetObject(new BareMetal(), $client->baremetal->createBareMetal($create), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->createBareMetal($create);
	}

	public function testDeleteBareMetal()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->baremetal->deleteBareMetal($id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->deleteBareMetal($id);
	}

	public function testUpdateBareMetal()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$bm_data = $provider->getBaremetalData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($bm_data)),
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$baremetal = $client->baremetal->getBareMetal($bm_data['bare_metal']['id']);
		$this->testGetObject(new BareMetal(), $baremetal, $bm_data);

		$update = new BareMetalUpdate();
		$update->setLabel($data['bare_metal']['label']);
		$update->setTags($data['bare_metal']['tags']);
		$update->setEnableIpv6(in_array('ipv6', $data['bare_metal']['features']));
		$update->setUserData(base64_encode('hello_world'));
		$update_bm = $client->baremetal->updateBareMetal($baremetal->getId(), $update);

		$this->testGetObject(new BareMetal(), $update_bm, $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->updateBareMetal($baremetal->getId(), $update);
	}

	public function testGetIPv4Addresses()
	{
		$provider = $this->getDataProvider();
		$spec_data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($spec_data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$response_objects = $client->baremetal->getIPv4Addresses($id);
		$this->testAddressInfo(new BareMetalIPv4Info(), $response_objects, $spec_data, $id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getIPv4Addresses($id);
	}

	public function testGetIPv6Addresses()
	{
		$provider = $this->getDataProvider();
		$spec_data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($spec_data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$response_objects = $client->baremetal->getIPv6Addresses($id);
		$this->testAddressInfo(new BareMetalIPv6Info(), $response_objects, $spec_data, $id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->getIPv6Addresses($id);
	}

	private function testAddressInfo(ModelInterface $model, array $response_objects, array $spec_data, string $id)
	{
		$this->assertEquals($spec_data['meta']['total'], count($response_objects));
		foreach ($response_objects as $response_object)
		{
			$this->assertInstanceOf($model::class, $response_object);
			foreach ($spec_data[$response_object->getResponseListName()] as $object)
			{
				if ($object['ip'] !== $response_object->getIp()) continue;

				$array = $response_object->toArray();
				foreach ($array as $prop => $prop_val)
				{
					// This does not exist in the response
					// But should be the same as the id fed into the parameter.
					if ($prop === 'id')
					{
						$this->assertEquals($prop_val, $id);
						continue;
					}

					$this->assertEquals($prop_val, $object[$prop], "Attribute {$prop} failed to meet comparison against spec.");
				}

				foreach (array_keys($object) as $attr)
				{
					$this->assertTrue(array_key_exists($attr, $array), "Attribute {$attr} failed to exist in toArray of the response object.");
				}
				break;
			}
		}
	}

	public function testStartBareMetal()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->baremetal->startBareMetal($id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->startBareMetal($id);
	}

	public function testStartBareMetals()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = ['cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-66fd-4dfb-b839-443f2e6c0b67'];
		$client->baremetal->startBareMetals($ids);

		$this->expectException(BareMetalException::class);
		$client->baremetal->startBareMetals($ids);
	}

	public function testRebootBareMetal()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->baremetal->rebootBareMetal($id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->rebootBareMetal($id);
	}

	public function testRebootBareMetals()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = ['cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-66fd-4dfb-b839-443f2e6c0b67'];
		$client->baremetal->rebootBareMetals($ids);

		$this->expectException(BareMetalException::class);
		$client->baremetal->rebootBareMetals($ids);
	}

	public function testHaltBareMetal()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->baremetal->haltBareMetal($id);

		$this->expectException(BareMetalException::class);
		$client->baremetal->haltBareMetal($id);
	}

	public function testHaltBareMetals()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$ids = ['cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-66fd-4dfb-b839-443f2e6c0b67'];
		$client->baremetal->haltBareMetals($ids);

		$this->expectException(BareMetalException::class);
		$client->baremetal->haltBareMetals($ids);
	}

	public function testReinstallBareMetal()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new BareMetal(), $client->baremetal->reinstallBaremetal($id), $data);

		$this->expectException(BareMetalException::class);
		$client->baremetal->reinstallBaremetal($id);
	}
}
