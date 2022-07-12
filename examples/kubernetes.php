<?php

declare(strict_types=1);

require(__DIR__.'/../api_key.php');
require (__DIR__.'/../vendor/autoload.php');

use Vultr\VultrPhp\Services\Kubernetes\NodePool;
use Vultr\VultrPhp\VultrClient;

$client = VultrClient::create(API_KEY);

$pools = [];
$pool = new NodePool();
$pool->setNodeQuantity(2);
$pool->setLabel('new-label');
$pool->setPlan('vc2-1c-2gb');
$pools[] = $pool;

$cluster = $client->kubernetes->createCluster('ewr', 'v1.23.7+1', $pools);

echo "Provisioning Cluster: ".$cluster->getId().PHP_EOL;
while (true)
{
	echo ".";
	$tmp_cluster = $client->kubernetes->getCluster($cluster->getId());
	if ($tmp_cluster->getStatus() != 'pending')
	{
		$cluster = $tmp_cluster;
		break;
	}
	sleep(1);
}

echo PHP_EOL;
echo "Deleting Cluster: ".$cluster->getId().PHP_EOL;
$client->kubernetes->deleteCluster($cluster->getId());

var_dump($client->kubernetes->getClusters());
