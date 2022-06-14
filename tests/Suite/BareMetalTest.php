<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\BareMetal\BareMetal;
use Vultr\VultrPhp\Services\BareMetal\BareMetalCreate;
use Vultr\VultrPhp\Services\BareMetal\BareMetalException;
use Vultr\VultrPhp\Services\BareMetal\BareMetalUpdate;
use Vultr\VultrPhp\Tests\VultrTest;

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
}
