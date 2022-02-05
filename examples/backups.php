<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

$backups = [];
$options = new ListOptions(2);
echo "======================\n";
echo "No Filter\n";
echo "======================\n";
while (true)
{
	foreach ($client->backups->getBackups(null, $options) as $backup)
	{
		$backups[] = $backup;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}

var_dump($options);
var_dump($backups);

$backups = [];
$options = new ListOptions(2);
echo "======================\n";
echo "Filtering based on ID\n";
echo "======================\n";
while (true)
{
	$id = 'YOUR UUID';
	foreach ($client->backups->getBackups($id, $options) as $backup)
	{
		$backups[] = $backup;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}
var_dump($options);
var_dump($backups);
