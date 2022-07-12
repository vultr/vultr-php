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
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-resources
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
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
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-available-upgrades
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return array
	 */
	public function getAvailableClusterUpgrades(string $id) : array
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/start-kubernetes-cluster-upgrade
	 * @param $id - string - VKE UUID, Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws KubernetesException
	 * @return void
	 */
	public function startClusterUpgrade(string $id) : void
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/create-nodepools
	 */
	public function createNodePool()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-nodepools
	 */
	public function getNodePools()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-nodepool
	 */
	public function getNodePool()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/update-nodepool
	 */
	public function updateNodePool()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/delete-nodepool
	 */
	public function deleteNodePool()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/delete-nodepool-instance
	 */
	public function deleteNodePoolInstance()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/recycle-nodepool-instance
	 */
	public function recycleNodePoolInstance()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-clusters-config
	 */
	public function getClusterKubeconfig()
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-kubernetes-versions
	 * @throws KubernetesException
	 * @return array
	 */
	public function getAvailableVersions() : array
	{

	}
}
