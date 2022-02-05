<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;

$client = VultrClient::create(API_KEY);

$apps = [];
$options = new ListOptions(20);
while (true)
{
	foreach ($client->applications->getApplications('all', $options) as $app)
	{
		$apps[] = $app;
	}

	if ($options->getNextCursor() == '')
	{
		break;
	}
	$options->setCurrentCursor($options->getNextCursor());
}

var_dump($options);
var_dump($apps);
