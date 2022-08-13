<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Kubernetes;

use Vultr\VultrPhp\Services\Kubernetes\Resources\BlockResource;
use Vultr\VultrPhp\Services\Kubernetes\Resources\LoadBalancerResource;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

class KubernetesService extends VultrService
{
	/**
	 * Retrieve a kubernetes cluster on the account.
	 *
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-clusters
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return VKECluster
	 */
	public function getCluster(string $id) : VKECluster
	{
		return $this->getObject('kubernetes/clusters/'.$id, new VKECluster());
	}

	/**
	 * Get a list of kubernetes clusters on the account.
	 *
	 * @see https://www.vultr.com/api/#operation/list-kubernetes-clusters
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws KubernetesException
	 * @return VKECluster[]
	 */
	public function getClusters(?ListOptions &$options = null)
	{
		return $this->getListObjects('kubernetes/clusters', new VKECluster(), $options);
	}

	/**
	 * Create a kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/create-kubernetes-cluster
	 * @param $region - string
	 * @param $version - string
	 * @param $node_pools - NodePool[]
	 * @throws KubernetesException
	 * @return VKECluster
	 */
	public function createCluster(string $region, string $version, array $node_pools, ?string $label = null) : VKECluster
	{
		$pools = [];
		foreach ($node_pools as $pool)
		{
			if (!($pool instanceof NodePool))
			{
				throw new KubernetesException('$node_pools must be an array of NodePool objects.');
			}

			$pools[] = $pool->getInitializedProps();
		}

		$params = [
			'region'     => $region,
			'version'    => $version,
			'node_pools' => $pools,
		];

		if ($label !== null)
		{
			$params['label'] = $label;
		}

		return $this->createObject('kubernetes/clusters', new VKECluster(), $params);
	}

	/**
	 * Update a kubernetes cluster.
	 *
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $label - string
	 * @throws KubernetesException
	 * @return void
	 */
	public function updateCluster(string $id, string $label) : void
	{
		$this->patchObject('kubernetes/clusters/'.$id, new VKECluster(), ['label' => $label]);
	}

	/**
	 * Delete a kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/delete-kubernetes-cluster
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return void
	 */
	public function deleteCluster(string $id) : void
	{
		$this->deleteObject('kubernetes/clusters/'.$id, new VKECluster());
	}

	/**
	 * Delete a kubernetes cluster and its resources.
	 *
	 * This means it will delete blockstorage, load balancers, and any other related resources.
	 *
	 * @see https://www.vultr.com/api/#operation/delete-kubernetes-cluster-vke-id-delete-with-linked-resources
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return void
	 */
	public function deleteClusterAndRelatedResources(string $id)
	{
		$this->deleteObject('kubernetes/clusters/'.$id.'/delete-with-linked-resources', new VKECluster());
	}

	/**
	 * Get resources belonging to a kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-resources
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @throws VultrException
	 * @return array
	 */
	public function getResources(string $id) : array
	{
		try
		{
			$response = $this->getClientHandler()->get('kubernetes/clusters/'.$id.'/resources');
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to get resources:'.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$objects = [];
		$model = new NodeResource();
		$stdclass = VultrUtil::decodeJSON((string)$response->getBody());
		$response_name = $model->getResponseListName();
		foreach ($stdclass->$response_name as $type => $resources)
		{
			$object = $model;
			if ($type === 'block_storage') $object = new BlockResource();
			else if ($type === 'load_balancer') $object = new LoadBalancerResource();

			foreach ($resources as $resource)
			{
				$objects[] = VultrUtil::mapObject($resource, $object);
			}
		}

		return $objects;
	}

	/**
	 * Get available cluster upgrades.
	 *
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-available-upgrades
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @throws VultrException
	 * @return array
	 */
	public function getAvailableClusterUpgrades(string $id) : array
	{
		try
		{
			$response = $this->getClientHandler()->get('kubernetes/clusters/'.$id.'/available-upgrades');
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to get cluster upgrades: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::decodeJSON((string)$response->getBody(), true)['available_upgrades'];
	}

	/**
	 * Start a kubernetes cluster version upgrade.
	 *
	 * @see https://www.vultr.com/api/#operation/start-kubernetes-cluster-upgrade
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $upgrade_version - string - Example: v1.22.8+3
	 * @throws KubernetesException
	 * @return void
	 */
	public function startClusterUpgrade(string $id, string $upgrade_version) : void
	{
		try
		{
			$this->getClientHandler()->post('kubernetes/clusters/'.$id.'/upgrades', ['upgrade_version' => $upgrade_version]);
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to start the upgrade for the cluster: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * Create a node pool in the kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/create-nodepools
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $pool - NodePool
	 * @throws KubernetesException
	 * @return NodePool
	 */
	public function createNodePool(string $id, NodePool $pool) : NodePool
	{
		return $this->createObject('kubernetes/clusters/'.$id.'/node-pools', new NodePool(), $pool->getInitializedProps());
	}

	/**
	 * Get available node pools in the kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/get-nodepools
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return NodePool[]
	 */
	public function getNodePools(string $id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects('kubernetes/clusters/'.$id.'/node-pools', new NodePool(), $options);
	}

	/**
	 * Get a specific node pool in the kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/get-nodepool
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $nodepool_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return NodePool
	 */
	public function getNodePool(string $id, string $nodepool_id) : NodePool
	{
		return $this->getObject('kubernetes/clusters/'.$id.'/node-pools/'.$nodepool_id, new NodePool());
	}

	/**
	 * Update a node pool in the kubernetes cluster with attributes from an initialized object.
	 *
	 * @see https://www.vultr.com/api/#operation/update-nodepool
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $nodepool - NodePool
	 * @throws KubernetesException
	 * @throws VultrException
	 * @return NodePool
	 */
	public function updateNodePool(string $id, NodePool $nodepool) : NodePool
	{
		try
		{
			$response = $this->getClientHandler()->patch('kubernetes/clusters/'.$id.'/node-pools/'.$nodepool->getId(), $nodepool->getInitializedProps());
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to update node pool: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new NodePool();
		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * Delete a node pool from a kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/delete-nodepool
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $nodepool_id - string
	 * @throws KubernetesException
	 * @return void
	 */
	public function deleteNodePool(string $id, string $nodepool_id) : void
	{
		$this->deleteObject('kubernetes/clusters/'.$id.'/node-pools/'.$nodepool_id, new NodePool());
	}

	/**
	 * Delete an instance from a node pool.
	 *
	 * @see https://www.vultr.com/api/#operation/delete-nodepool-instance
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $nodepool_id - string
	 * @param $node_id - string
	 * @throws KubernetesException
	 * @return void
	 */
	public function deleteNodePoolInstance(string $id, string $nodepool_id, string $node_id) : void
	{
		$this->deleteObject('kubernetes/clusters/'.$id.'/node-pools/'.$nodepool_id.'/nodes/'.$node_id, new Node());
	}

	/**
	 * Reinstall a specific instance from a node pool.
	 *
	 * @see https://www.vultr.com/api/#operation/recycle-nodepool-instance
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $nodepool_id - string
	 * @param $node_id - string
	 * @throws KubernetesException
	 * @return void
	 */
	public function recycleNodePoolInstance(string $id, string $nodepool_id, string $node_id) : void
	{
		try
		{
			$this->getClientHandler()->post('kubernetes/clusters/'.$id.'/node-pools/'.$nodepool_id.'/nodes/'.$node_id);
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to recycle node pool instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * Get the kubeconfig for the kubernetes cluster.
	 *
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-clusters-config
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @throws VultrException
	 * @return string
	 */
	public function getClusterKubeconfig(string $id) : string
	{
		try
		{
			$response = $this->getClientHandler()->get('kubernetes/clusters/'.$id.'/config');
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to get kubeconfig: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return base64_decode(VultrUtil::decodeJSON((string)$response->getBody(), true)['kube_config']);
	}

	/**
	 * Get available kubernetes versions that vultr supports.
	 *
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-versions
	 * @throws KubernetesException
	 * @throws VultrException
	 * @return array
	 */
	public function getAvailableVersions() : array
	{
		try
		{
			$response = $this->getClientHandler()->get('kubernetes/versions');
		}
		catch (VultrClientException $e)
		{
			throw new KubernetesException('Failed to get kubernetes versions: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::decodeJSON((string)$response->getBody(), true)['versions'];
	}
}
