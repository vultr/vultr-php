<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use JsonMapper\Middleware\AbstractMiddleware;
use JsonMapper\Wrapper\ObjectWrapper;
use JsonMapper\ValueObjects\PropertyMap;
use JsonMapper\JsonMapperInterface;

class LoadBalancerService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#operation/get-load-balancer
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws LoadBalancerException
	 * @return LoadBalancer
	 */
	public function getLoadBalancer(string $id) : LoadBalancer
	{
		$object = $this->getObject('load-balancers/'.$id, new LoadBalancer());
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

		return $object;
	}

	/**
	 * @see https://www.vultr.com/api/#operation/list-load-balancers
	 * @throws LoadBalancerException
	 * @return LoadBalancer[]
	 */
	public function getLoadBalancers() : array
	{

	}
}
