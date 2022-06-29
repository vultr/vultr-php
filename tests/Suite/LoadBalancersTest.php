<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\LoadBalancers\FirewallRule;
use Vultr\VultrPhp\Services\LoadBalancers\ForwardRule;
use Vultr\VultrPhp\Services\LoadBalancers\LBHealth;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancer;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancerCreate;
use Vultr\VultrPhp\Services\LoadBalancers\LoadBalancerException;
use Vultr\VultrPhp\Services\LoadBalancers\StickySession;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Util\VultrUtil;

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

		$options = $this->createListOptions();
		foreach ($client->loadbalancers->getLoadBalancers($options) as $response_object)
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

	public function testCreateLoadBalancer()
	{
		$provider = $this->getDataProvider();
		$spec_data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(202, ['Content-Type' => 'application/json'], json_encode($spec_data)),
			new Response(400, [], json_encode(['error' => 'Bad request']))
		]);

		$spec_generic_info = $spec_data['load_balancer']['generic_info'];
		$create = new LoadBalancerCreate('ewr');
		$create->setBalancingAlgorithm($spec_generic_info['balancing_algorithm']);
		$create->setSslRedirect($spec_generic_info['ssl_redirect']);
		$create->setProxyProtocol($spec_generic_info['proxy_protocol']);

		$create->setHealthCheck($this->createLBHealthFromData($spec_data['load_balancer']['health_check']));

		$forwarding_rules = [];
		foreach ($spec_data['load_balancer']['forwarding_rules'] as $rule)
		{
			$forwarding_rules[] = VultrUtil::mapObject((object)$rule, new ForwardRule());
		}
		$create->setForwardingRules($forwarding_rules);

		$firewall_rules = [];
		foreach ($spec_data['load_balancer']['firewall_rules'] as $rule)
		{
			$firewall_rules[] = VultrUtil::mapObject((object)$rule, new FirewallRule());
		}
		$create->setFirewallRules($firewall_rules);

		$session = new StickySession();
		$session->setCookieName($spec_generic_info['sticky_sessions']['cookie_name']);
		$create->setStickySession($session);

		$create->setLabel($spec_data['load_balancer']['label']);
		$create->setInstances($spec_data['load_balancer']['instances']);

		$loadbalancer = $client->loadbalancers->createLoadBalancer($create);
		$this->assertInstanceOf(LoadBalancer::class, $loadbalancer);
		foreach (['getBalancingAlgorithm', 'getSslRedirect', 'getProxyProtocol'] as $method)
		{
			$this->assertEquals($create->$method(), $loadbalancer->getGenericInfo()->$method());
		}

		foreach (['getProtocol', 'getPort', 'getCheckInterval', 'getResponseTimeout', 'getUnhealthyThreshold', 'getHealthyThreshold'] as $method)
		{
			$this->assertEquals($create->getHealthCheck()->$method(), $loadbalancer->getHealthCheck()->$method());
		}

		foreach (['getForwardingRules', 'getFirewallRules'] as $method)
		{
			$compare_array = [];
			foreach ($create->$method() as $rule)
			{
				$compare_array[] = $rule->toArray();
			}
			$compare_array_2 = [];
			foreach ($loadbalancer->$method() as $rule)
			{
				$compare_array_2[] = $rule->toArray();
			}
			$this->assertEquals($compare_array, $compare_array_2);
		}

		$this->assertEquals($create->getStickySession()->toArray(), $loadbalancer->getGenericInfo()->getStickySessions()->toArray());

		$this->assertFalse($loadbalancer->getHasSsl());
		$this->assertNull($create->getSsl());

		$this->assertEquals($create->getLabel(), $loadbalancer->getLabel());
		$this->assertEquals($create->getInstances(), $loadbalancer->getInstances());

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->createLoadBalancer($create);
	}

	private function createLBHealthFromData(array $spec_health) : LBHealth
	{
		$create_health = new LBHealth();
		$create_health->setProtocol($spec_health['protocol']);
		$create_health->setPort($spec_health['port']);
		$create_health->setPath($spec_health['path']);
		$create_health->setCheckInterval($spec_health['check_interval']);
		$create_health->setResponseTimeout($spec_health['response_timeout']);
		$create_health->setUnhealthyThreshold($spec_health['unhealthy_threshold']);
		$create_health->setHealthyThreshold($spec_health['healthy_threshold']);

		return $create_health;
	}

	public function testUpdateLoadBalancer()
	{
		$this->markTestSkipped('Not Implemented');
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

	public function testGetForwardingRules()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'random-id';
		$this->testListObject(new ForwardRule(), $client->loadbalancers->getForwardingRules($id), $data);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getForwardingRules($id);
	}

	public function testGetForwardRule()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'random-id';
		$forward_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new ForwardRule(), $client->loadbalancers->getForwardingRule($id, $forward_id), $data);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getForwardingRule($id, $forward_id);
	}

	public function testCreateForwardingRule()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$rule = new ForwardRule();
		$rule->setFrontendProtocol('HTTP');
		$rule->setFrontendPort(80);
		$rule->setBackendProtocol('HTTP');
		$rule->setBackendPort(8080);

		$client->loadbalancers->createForwardingRule('random-id', $rule);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->createForwardingRule('random-id', $rule);
	}

	public function testDeleteForwardRule()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->loadbalancers->deleteForwardRule('random-loadbalancer-id', 'random-forwarding-rule-id');

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->deleteForwardRule('random-loadbalancer-id', 'random-forwarding-rule-id');
	}

	public function testGetFirewallRules()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'random-id';
		$this->testListObject(new FirewallRule(), $client->loadbalancers->getFirewallRules($id), $data);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getForwardingRules($id);
	}

	public function testGetFirewallRule()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'random-id';
		$firewall_id = 'asb123f2e6c0b60';
		$this->testGetObject(new FirewallRule(), $client->loadbalancers->getFirewallRule($id, $firewall_id), $data);

		$this->expectException(LoadBalancerException::class);
		$client->loadbalancers->getForwardingRule($id, $firewall_id);
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
