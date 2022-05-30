<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

echo "======================\n";
echo "Get VPS Plans\n";
echo "======================\n";
$plans = [];
$options = new ListOptions(10);
while (true)
{
	foreach ($client->plans->getVPSPlans(null, null, $options) as $plan)
	{
		$plans[] = $plan;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}
var_dump($plans);
var_dump($options);

echo "======================\n";
echo "Get BM Plans\n";
echo "======================\n";
$plans = [];
$options = new ListOptions(10);
while (true)
{
	foreach ($client->plans->getBMPlans($options) as $plan)
	{
		$plans[] = $plan;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}
var_dump($plans);
var_dump($options);
