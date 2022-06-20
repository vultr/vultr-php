<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;

class LoadBalancerService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#operation/get-load-balancer
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws LoadBalancerException
	 * @throws VultrException
	 * @return LoadBalancer
	 */
	public function getLoadBalancer(string $id) : LoadBalancer
	{
		$object = $this->getObject('load-balancers/'.$id, new LoadBalancer());
		$this->setRules($object);
		return $object;
	}

	/**
	 * @see https://www.vultr.com/api/#operation/list-load-balancers
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws LoadBalancerException
	 * @return LoadBalancer[]
	 */
	public function getLoadBalancers(?ListOptions &$options = null) : array
	{
		$objects = $this->getListObjects('load-balancers', new LoadBalancer(), $options);
		foreach ($objects as &$object)
		{
			$this->setRules($object);
		}
		return $objects;
	}

	/**
	 * @throws VultrException
	 */
	private function setRules(LoadBalancer &$object) : void
	{
		$rules = [];
		foreach ($object->getForwardingRules() as $rule)
		{
			$rules[] = VultrUtil::mapObject($rule, new ForwardRule());
		}
		$object->setForwardingRules($rules);

		$rules = [];
		foreach ($object->getFirewallRules() as $rule)
		{
			$rules[] = VultrUtil::mapObject($rule, new FirewallRule());
		}
		$object->setFirewallRules($rules);
	}
}
