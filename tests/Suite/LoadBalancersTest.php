<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancerException;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancer;
use Vultr\VultrPhp\Tests\VultrTest;

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
			else if (is_array($object_data))
			{
				foreach ($object_data as &$potential)
				{
					if (!($potential instanceof ModelInterface)) continue;

					$potential = $potential->toArray();
				}
			}
			$this->assertEquals($object_data, $spec_data[$response_object->getResponseName()][$attr], "Attribute {$attr} failed to meet comparison against spec.");
		}

		foreach (array_keys($spec_data[$response_object->getResponseName()]) as $attr)
		{
			$this->assertTrue(array_key_exists($attr, $array), "Attribute {$attr} failed to exist in toArray of the response object.");
		}

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getLoadBalancer($id);
	}
}
