<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\Services\Firewall\FirewallRule;
use Vultr\VultrPhp\VultrClient;

$client = VultrClient::create(API_KEY);

echo "Getting Groups\n";
$groups = $client->firewall->getFirewallGroups();
$group = $groups[0];

$found_rule = false;
$rule = new FirewallRule();
echo "Looping Group Rules for ".$group->getId().PHP_EOL;
foreach ($client->firewall->getFirewallRules($group->getId()) as $tmp_rule)
{
	if ($tmp_rule->getIpType() === 'v4' &&
		$tmp_rule->getProtocol() === 'tcp' &&
		$tmp_rule->getSubnet() === '0.0.0.0' &&
		$tmp_rule->getSubnetSize() === 0 &&
		$tmp_rule->getPort() === '80'
	)
	{
		$found_rule = true;
		$rule = $tmp_rule;
		break;
	}
}

if (!$found_rule)
{
	echo "Creating Rule\n";
	$rule = new FirewallRule();
	$rule->setIpType('v4');
	$rule->setProtocol('TCP');
	$rule->setSubnet('0.0.0.0/0');
	$rule->setPort('80');


	$rule = $client->firewall->createFirewallRule($group->getId(), $rule);
	var_dump($rule);
}


echo "Deleting Rule ".$rule->getId().PHP_EOL;
$client->firewall->deleteFirewallRule($group->getId(), $rule->getId());
