<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

echo "======================\n";
echo "List\n";
echo "======================\n";

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

echo "======================\n";
echo "Get Snapshot\n";
echo "======================\n";

$snapshot = $client->snapshots->getSnapshot('UUID-GOES-HERE');
var_dump($snapshot);


echo "======================\n";
echo "Create Snapshot\n";
echo "======================\n";

$snapshot = $client->snapshots->createSnapshot('INSTANCE UUID-GOES-HERE', 'test-api');

var_dump($snapshot);

echo "======================\n";
echo "Create Snapshot from URL\n";
echo "======================\n";

$snapshot = $client->snapshots->createSnapshotFromURL('https://www.youtube.com/dude-island', 'test-api');

var_dump($snapshot);

