<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

$backups = [];
$options = new ListOptions(2);
echo "======================\n";
echo "List\n";
echo "======================\n";
while (true)
{
	//$id = 'YOUR UUID INSTANCE ID';
	$id = null;
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

echo "======================\n";
echo "Get Backup\n";
echo "======================\n";

$backup = $client->backups->getBackup('YOUR BACKUP UUID');
var_dump($backup);

