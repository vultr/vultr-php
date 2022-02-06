<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

$snapshots = [];
$options = new ListOptions(10);
while (true)
{
	foreach ($client->snapshots->getSnapshots(null, $options) as $snapshot)
	{
		$snapshots[] = $snapshot;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}

var_dump($options);
var_dump($snapshots);
