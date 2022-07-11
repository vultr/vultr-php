<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\Services\Instances\InstanceCreate;
use Vultr\VultrPhp\Services\Plans\PlanService;
use Vultr\VultrPhp\VultrClient;

$client = VultrClient::create(API_KEY);

echo "Getting Region Availability\n";
$availability = $client->regions->getAvailablility('ewr', PlanService::FILTER_VC2);

$plan = $availability[0];

$debian = null;
echo "Getting Operating Systems\n";
foreach ($client->operating_system->getOperatingSystems() as $os)
{
	if (stripos($os->getName(), 'Debian 11') === false) continue;

	$debian = $os;
	break;
}

if ($debian === null)
{
	exit('Failed to find a debian 11 operating system.');
}

$options = new InstanceCreate('ewr', $plan->getId());
$options->setOsId($debian->getId());
echo "Creating instance with OSID: ".$debian->getId() . ' in region ewr.'.PHP_EOL;
$instance = $client->instances->createInstance($options);

echo "Waiting, to provision instance: ".$instance->getId().PHP_EOL;
while (true)
{
	echo ".";
	$tmp_instance = $client->instances->getInstance($instance->getId());
	if ($tmp_instance->getStatus() != 'pending')
	{
		$instance = $tmp_instance;
		break;
	}
	sleep(1);
}

echo PHP_EOL;
echo "Removing the instance\n";
$client->instances->deleteInstance($instance->getId());

var_dump($client->instances->getInstances());

