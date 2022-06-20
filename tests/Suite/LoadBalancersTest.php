<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancer;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancerException;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\Util\ModelInterface;

class LoadBalancersTest extends VultrTest
{
	public function testGetLoadBalancer()
	{
		$provider = $this->getDataProvider();
		$spec_data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($spec_data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$response_object = $client->loadbalancers->getLoadBalancer($id);
		$this->testObject($response_object, $spec_data[$response_object->getResponseName()]);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getLoadBalancer($id);
	}

	public function testGetLoadBalancers()
	{
		$provider = $this->getDataProvider();
		$spec_data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($spec_data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		foreach ($client->loadbalancers->getLoadBalancers() as $response_object)
		{
			$data = null;
			foreach ($spec_data[$response_object->getResponseListName()] as $json_object)
			{
				if ($json_object['id'] !== $response_object->getId()) continue;

				$data = $json_object;
				break;
			}
			$this->assertNotNull($data);
			$this->testObject($response_object, $data);
		}

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getLoadBalancers();
	}

	public function testDeleteLoadBalancer()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->loadbalancers->deleteLoadBalancer($id);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->deleteLoadBalancer($id);
	}

	private function testObject(ModelInterface $response_object, array $spec_data)
	{
		$this->assertInstanceOf(LoadBalancer::class, $response_object);
		$array = $response_object->toArray();
		foreach ($array as $attr => $value)
		{
			$object_data = $value;
			if ($object_data instanceof ModelInterface)
			{
				$object_data = $object_data->toArray();
				foreach ($object_data as &$potential)
				{
					if (!($potential instanceof ModelInterface)) continue;

					$potential = $potential->toArray();
				}
			}
			else if (is_array($object_data) && in_array($attr, ['forwarding_rules', 'firewall_rules']))
			{
				foreach ($object_data as &$potential)
				{
					$this->assertInstanceOf(ModelInterface::class, $potential);

					$potential = $potential->toArray();
				}
			}
			$this->assertEquals($object_data, $spec_data[$attr], "Attribute {$attr} failed to meet comparison against spec.");
		}

		foreach (array_keys($spec_data) as $attr)
		{
			$this->assertTrue(array_key_exists($attr, $array), "Attribute {$attr} failed to exist in toArray of the response object.");
		}
	}
}
