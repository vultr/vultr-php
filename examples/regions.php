<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

echo "======================\n";
echo "Get Regions\n";
echo "======================\n";
$regions = [];
$options = new ListOptions(10);
while (true)
{
	foreach ($client->regions->getRegions($options) as $region)
	{
		$regions[] = $region;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}

var_dump($options);
var_dump($regions);

echo "======================\n";
echo "Get Availability\n";
echo "======================\n";

$availability = $client->regions->getAvailablility('ewr');
var_dump($availability);
