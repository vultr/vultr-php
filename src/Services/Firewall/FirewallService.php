<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Firewall;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class FirewallService extends VultrService
{
	/**
	 * List firewall groups.
	 *
	 * @see https://www.vultr.com/api/#operation/list-firewall-groups
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws FirewallException
	 * @return array
	 */
	public function getFirewallGroups(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('firewalls', new FirewallGroup(), $options);
	}

	/**
	 * Retrieve a specific firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/get-firewall-group
	 * @param $group_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws FirewallException
	 * @return FirewallGroup
	 */
	public function getFirewallGroup(string $group_id) : FirewallGroup
	{
		return $this->getObject('firewalls/'.$group_id, new FirewallGroup());
	}

	/**
	 * Create a firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/create-firewall-group
	 * @param $description - string
	 * @throws FirewallException
	 * @return FirewallGroup
	 */
	public function createFirewallGroup(string $description) : FirewallGroup
	{
		return $this->createObject('firewalls', new FirewallGroup(), ['description' => $description]);
	}

	/**
	 * Update information for a friewall group
	 *
	 * @see https://www.vultr.com/api/#operation/update-firewall-group
	 * @param $group - FirewallGroup
	 * @throws FirewallException
	 * @return void
	 */
	public function updateFirewallGroup(FirewallGroup $group) : void
	{
		$this->patchObject('firewalls/'.$group->getId(), $group);
	}

	/**
	 * Delete a firewall group on the account.
	 *
	 * @see https://www.vultr.com/api/#operation/delete-firewall-group
	 * @param $group_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws FirewallException
	 * @return void
	 */
	public function deleteFirewallGroup(string $group_id) : void
	{
		$this->deleteObject('firewalls/'.$group_id, new FirewallGroup());
	}

	/**
	 * Get firewall rules for the firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/list-firewall-group-rules
	 * @param $group_id - string - Example cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws FirewallException
	 * @return array
	 */
	public function getFirewallRules(string $group_id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects('firewalls/'.$group_id.'/rules', new FirewallRule(), $options);
	}

	/**
	 * Get a specific firewall rule from the firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/get-firewall-group-rule
	 * @param $group_id - string - Example cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $rule_id - int
	 * @throws FirewallException
	 * @return FirewallRule
	 */
	public function getFirewallRule(string $group_id, int $rule_id) : FirewallRule
	{
		return $this->getObject('firewalls/'.$group_id.'/rules/'.$rule_id, new FirewallRule());
	}

	/**
	 * Create a firewall rule for the firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/post-firewalls-firewall-group-id-rules
	 * @param $group_id - string - Example cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $rule - FirewallRule
	 * @throws FirewallException
	 * @return FirewallRule
	 */
	public function createFirewallRule(string $group_id, FirewallRule $rule) : FirewallRule
	{
		return $this->createObject('firewalls/'.$group_id.'/rules', new FirewallRule(), $rule->getInitializedProps());
	}

	/**
	 * Delete a firewall rule from the firewall group
	 *
	 * @see https://www.vultr.com/api/#operation/delete-firewall-group-rule
	 * @param $group_id - string - Example cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $rule_id - int
	 * @throws FirewallException
	 * @return void
	 */
	public function deleteFirewallRule(string $group_id, int $rule_id) : void
	{
		$this->deleteObject('firewalls/'.$group_id.'/rules/'.$rule_id, new FirewallRule());
	}

	/**
	 * There is no Update firewall rule. There was never a point in adding an endpoint in the api for it.
	public function updateFirewallRule(string $group_id, FirewallRule $rule) : void
	{

	}
	*/
}
