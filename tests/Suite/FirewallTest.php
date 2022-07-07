<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Firewall\FirewallException;
use Vultr\VultrPhp\Services\Firewall\FirewallGroup;
use Vultr\VultrPhp\Services\Firewall\FirewallRule;
use Vultr\VultrPhp\Tests\VultrTest;

class FirewallTest extends VultrTest
{
	public function testGetFirewallGroups()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$this->testListObject(new FirewallGroup(), $client->firewall->getFirewallGroups($options), $data);

		$this->expectException(FirewallException::class);
		$client->firewall->getFirewallGroups();
	}

	public function testGetFirewallGroup()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$group_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testGetObject(new FirewallGroup(), $client->firewall->getFirewallGroup($group_id), $data);

		$this->expectException(FirewallException::class);
		$client->firewall->getFirewallGroup($group_id);
	}

	public function testCreateFirewallGroup()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();
		$client = $provider->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$description = $data['firewall_group']['description'];
		$this->testGetObject(new FirewallGroup(), $client->firewall->createFirewallGroup($description), $data);

		$this->expectException(FirewallException::class);
		$client->firewall->createFirewallGroup($description);
	}

	public function testUpdateFirewallGroup()
	{
		$provider = $this->getDataProvider();
		$group_data = $provider->getFirewallGroup();
		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($group_data)),
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$group = $client->firewall->getFirewallGroup('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$this->testGetObject(new FirewallGroup(), $group, $group_data);

		$group->setDescription('hello world');

		$client->firewall->updateFirewallGroup($group);

		$this->expectException(FirewallException::class);
		$client->firewall->updateFirewallGroup($group);
	}

	public function testDeleteFirewallGroup()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$group_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->firewall->deleteFirewallGroup($group_id);

		$this->expectException(FirewallException::class);
		$client->firewall->deleteFirewallGroup($group_id);
	}

	public function testGetFirewallRules()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$group_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->testListObject(new FirewallRule(), $client->firewall->getFirewallRules($group_id, $options), $data);

		$this->expectException(FirewallException::class);
		$client->firewall->getFirewallRules($group_id);
	}

	public function testGetFirewallRule()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$group_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$rule_id = 1;
		$this->testGetObject(new FirewallRule(), $client->firewall->getFirewallRule($group_id, $rule_id), $data);

		$this->expectException(FirewallException::class);
		$client->firewall->getFirewallRule($group_id, $rule_id);
	}

	public function testCreateFirewallRule()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$rule = new FirewallRule();
		$rule->setIpType($data['firewall_rule']['ip_type']);
		$rule->setProtocol($data['firewall_rule']['protocol']);
		$rule->setSubnet($data['firewall_rule']['subnet']);
		$rule->setSubnetSize($data['firewall_rule']['subnet_size']);
		$rule->setPort($data['firewall_rule']['port']);
		$rule->setSource($data['firewall_rule']['source']);
		$rule->setNotes($data['firewall_rule']['notes']);

		$firewall_rule = $client->firewall->createFirewallRule('12315423525-123123123', $rule);
		$this->testGetObject(new FirewallRule(), $firewall_rule, $data);

		$this->expectException(FirewallException::class);
		$client->firewall->createFirewallRule('12315423525-123123123', $rule);
	}

	public function testSetSubnetByCIDR()
	{
		$rule = new FirewallRule();
		$rule->setSubnet('0.0.0.0/0');

		$this->assertEquals($rule->getSubnet(), '0.0.0.0');
		$this->assertEquals($rule->getSubnetSize(), 0);

		$this->assertEquals(['subnet' => '0.0.0.0', 'subnet_size' => 0], $rule->getInitializedProps());
	}

	public function testDeleteFirewallRule()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$group_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$rule_id = 1;
		$client->firewall->deleteFirewallRule($group_id, $rule_id);

		$this->expectException(FirewallException::class);
		$client->firewall->deleteFirewallRule($group_id, $rule_id);
	}
}
