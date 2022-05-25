<?php
require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\VultrClient;
use GuzzleHttp\Client;

$client = VultrClient::create(new Client(), API_KEY);

var_dump($client->account->getAccount()->toArray());

