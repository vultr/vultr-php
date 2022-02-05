<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Regions\RegionService;

$client = VultrClient::create(API_KEY);

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
