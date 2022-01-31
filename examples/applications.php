<?php

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;

$client = VultrClient::create(API_KEY);

$apps = [];
foreach ($client->applications->getApplications('all', $options) as $app)
{
	$apps[] = $app;
}

while ($options->getNextCursor() != '')
{
	$options->setCurrentCursor($options->getNextCursor());
	foreach ($client->applications->getApplications('all', $options) as $app)
	{
		$apps[] = $app;
	}
}
var_dump($options);
var_dump($apps);
