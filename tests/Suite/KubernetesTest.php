<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Kubernetes\KubernetesException;
use Vultr\VultrPhp\Services\Kubernetes\Node;
use Vultr\VultrPhp\Services\Kubernetes\NodePool;
use Vultr\VultrPhp\Services\Kubernetes\NodeResource;
use Vultr\VultrPhp\Services\Kubernetes\Resources\BlockResource;
use Vultr\VultrPhp\Services\Kubernetes\Resources\LoadBalancerResource;
use Vultr\VultrPhp\Services\Kubernetes\VKECluster;
use Vultr\VultrPhp\Tests\VultrTest;

class KubernetesTest extends VultrTest
{
	public function testGetCluster()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$cluster = $client->kubernetes->getCluster($id);
		$this->convertTestToArrayVKECluster($cluster);
		$this->testGetObject(new VKECluster(), $cluster, $data);

		$this->expectException(KubernetesException::class);
		$client->kubernetes->getCluster($id);
	}

	public function testGetClusters()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$options = $this->createListOptions();
		$clusters = $client->kubernetes->getClusters($options);
		foreach ($clusters as &$cluster)
		{
			$this->convertTestToArrayVKECluster($cluster);
		}

		$this->testListObject(new VKECluster(), $clusters, $data);

		$this->expectException(KubernetesException::class);
		$client->kubernetes->getClusters($options);
	}

	public function testCreateCluster()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$region = $data['vke_cluster']['region'];
		$version = $data['vke_cluster']['version'];
		$label = $data['vke_cluster']['label'];
		$pools = [];
		foreach ($data['vke_cluster']['node_pools'] as $node_pool)
		{
			$pool = new NodePool();
			$pool->setPlan($node_pool['plan']);
			$pool->setNodeQuantity($node_pool['node_quantity']);
			$pool->setLabel($node_pool['label']);
			$pool->setAutoScaler($node_pool['auto_scaler']);
			$pool->setMinNodes($node_pool['min_nodes']);
			$pool->setMaxNodes($node_pool['max_nodes']);
			$pools[] = $pool;
		}

		$cluster = $client->kubernetes->createCluster($region, $version, $pools, $label);
		$this->convertTestToArrayVKECluster($cluster);
		$this->testGetObject(new VKECluster(), $cluster, $data);

		$this->expectException(KubernetesException::class);
		$client->kubernetes->createCluster($region, $version, $pools, $label);
	}

	public function testUpdateCluster()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$label = 'hello-world';
		$this->assertNull($client->kubernetes->updateCluster($id, $label));

		$this->expectException(KubernetesException::class);
		$client->kubernetes->updateCluster($id, $label);
	}

	public function testDeleteCluster()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->kubernetes->deleteCluster($id));

		$this->expectException(KubernetesException::class);
		$client->kubernetes->deleteCluster($id);
	}

	public function testDeleteClusterAndRelatedResources()
	{
		$provider = $this->getDataProvider();

		$client = $provider->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$this->assertNull($client->kubernetes->deleteClusterAndRelatedResources($id));

		$this->expectException(KubernetesException::class);
		$client->kubernetes->deleteClusterAndRelatedResources($id);
	}

	public function testGetResources()
	{
		$provider = $this->getDataProvider();
		$data = $provider->getData();

		$client = $provider->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$resources = $client->kubernetes->getResources($id);
		foreach ($resources as $resource)
		{
			$this->assertInstanceOf(NodeResource::class, $resource);
			$this->assertTrue(($resource instanceof BlockResource) || ($resource instanceof LoadBalancerResource));
			$type = stripos($resource::class, 'BlockResource') !== false ? 'blockstorage' : 'unknown-resource';
			$type = stripos($resource::class, 'LoadBalancerResource') !== false ? 'loadbalancer' : $type;
			$this->assertEquals($resource->getType(), $type);

			$resource_list = 'unknown';
			if ($type === 'loadbalancer') $resource_list = 'load_balancer';
			else if ($type === 'blockstorage') $resource_list = 'block_storage';

			$found = false;
			foreach ($data[$resource->getResponseListName()][$resource_list] as $cmp_resource)
			{
				if ($cmp_resource['id'] !== $resource->getId()) continue;
				$cmp_resource['type'] = $type;
				$this->testObject(new NodeResource(), $resource, $cmp_resource);
				$found = true;
				break;
			}

			$this->assertTrue($found);
		}

		$this->expectException(KubernetesException::class);
		$client->kubernetes->getResources($id);
	}

	private function convertTestToArrayVKECluster(VKECluster &$cluster) : void
	{
		$node_pools = [];
		foreach ($cluster->getNodePools() as $node_pool)
		{
			$this->assertInstanceOf(NodePool::class, $node_pool);
			$pool = $node_pool->toArray();
			$pool['nodes'] = [];
			foreach ($node_pool->getNodes() as $node)
			{
				$this->assertInstanceOf(Node::class, $node);
				$pool['nodes'][] = $node->toArray();
			}
			$node_pools[] = $pool;
		}
		$cluster->setNodePools($node_pools);
	}
}
