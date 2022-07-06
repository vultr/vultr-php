<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Instances;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\Services\OperatingSystems\OperatingSystem;
use Vultr\VultrPhp\Services\Applications\Application;

class InstanceService extends VultrService
{
	public const FILTER_LABEL = 'label';
	public const FILTER_MAIN_IP = 'main_ip';
	public const FILTER_REGION = 'region';

	/**
	 * @see https://www.vultr.com/api/#operation/list-instances
	 * @param $filters - array|null - ENUM(FILTER_LABEL, FILTER_MAIN_IP, FILTER_REGION)
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws InstanceException
	 * @return Instance[]
	 */
	public function getInstances(?array $filters = null, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects('instances', new Instance(), $options, $filters);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance
	 * @param $id - string
	 * @throws InstanceException
	 * @return Instance
	 */
	public function getInstance(string $id) : Instance
	{
		return $this->getObject('instances/'.$id, new Instance());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/create-instance
	 * @param $create - InstanceCreate
	 * @throws InstanceException
	 * @return Instance
	 */
	public function createInstance(InstanceCreate $create) : Instance
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/update-instance
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $update - InstanceUpdate
	 * @throws InstanceException
	 * @return void
	 */
	public function updateInstance(string $id, InstanceUpdate $update) : void
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/delete-instance
	 * @param $id - string
	 * @throws InstanceException
	 * @return void
	 */
	public function deleteInstance(string $id) : void
	{
		$this->deleteObject('instances/'.$id, new Instance());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/halt-instances
	 * @param $ids - array
	 * @throws InstanceException
	 * @return void
	 */
	public function haltInstances(array $ids) : void
	{
		$this->multipleInstancesAction('halt', $ids);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/halt-instance
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return void
	 */
	public function haltInstance(string $id) : void
	{
		$this->singleInstanceAction('halt', $id);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/reboot-instances
	 * @param $ids - array - Example: ['cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-22fd-4dfb-b839-443f2e6c0b60']
	 * @throws InstanceException
	 * @return void
	 */
	public function rebootInstances(array $ids) : void
	{
		$this->multipleInstancesAction('reboot', $ids);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/reboot-instance
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return void
	 */
	public function rebootInstance(string $id) : void
	{
		$this->singleInstanceAction('reboot', $id);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/start-instances
	 * @param $ids - array - Example: ['cb676a46-66fd-4dfb-b839-443f2e6c0b60', 'cb676a46-22fd-4dfb-b839-443f2e6c0b60']
	 * @throws InstanceException
	 * @return void
	 */
	public function startInstances(array $ids) : void
	{
		$this->multipleInstancesAction('start', $ids);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/start-instance
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return void
	 */
	public function startInstance(string $id) : void
	{
		$this->singleInstanceAction('start', $id);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/reinstall-instance
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return Instance
	 */
	public function reinstallInstance(string $id, ?string $hostname = null) : Instance
	{
		try
		{
			$response = $this->getClientHandler()->post('instances/'.$id.'/reinstall', $hostname !== null ? ['hostname' => $hostname] : []);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get bandwidth for instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new Instance();
		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-bandwidth
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @throws VultrException
	 * @return array
	 */
	public function getBandwidth(string $id) : array
	{
		try
		{
			$response = $this->getClientHandler()->get('instances/'.$id.'/bandwidth');
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get bandwidth for instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$decode = VultrUtil::decodeJSON((string)$response->getBody(), true);

		$output = [];
		// Just a standardization, in the event the api ever changes its attributes. We can just change it here maintain backwards compat.
		foreach ($decode['bandwidth'] as $date => $attr)
		{
			$output[$date] = [];
			foreach (['incoming_bytes', 'outgoing_bytes'] as $attribute)
			{
				$output[$date][$attribute] = $attr[$attribute];
			}
		}

		return $output;
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-neighbors
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @throws VultrException
	 * @return array
	 */
	public function getNeighbors(string $id) : array
	{
		try
		{
			$response = $this->getClientHandler()->get('instances/'.$id.'/neighbors');
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get neighbors for instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::decodeJSON((string)$response->getBody(), true)['neighbors'] ?? [];
	}

	/**
	 * @see https://www.vultr.com/api/#operation/list-instance-vpcs
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws InstanceException
	 * @return array
	 */
	public function getVPCs(string $id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects('instances/'.$id.'/vpcs', new VPCAttachment(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/attach-instance-vpc
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $vpc_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return void
	 */
	public function attachVPC(string $id, string $vpc_id) : void
	{
		try
		{
			$this->getClientHandler()->post('instances/'.$id.'/vpcs/attach', ['vpc_id' => $vpc_id]);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to attach vpc: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @see https://www.vultr.com/api/#operation/detach-instance-vpc
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $vpc_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return void
	 */
	public function detachVPC(string $id, string $vpc_id) : void
	{
		try
		{
			$this->getClientHandler()->post('instances/'.$id.'/vpcs/detach', ['vpc_id' => $vpc_id]);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to detach vpc: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-iso-status
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return IsoStatus
	 */
	public function getIsoStatus(string $id) : IsoStatus
	{
		try
		{
			$response = $this->getClientHandler()->get('instances/'.$id.'/iso');
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get iso status: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new IsoStatus();
		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/attach-instance-iso
	 * @param $id - string - Instance ID - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $iso_id - string
	 * @throws InstanceException
	 * @return IsoStatus
	 */
	public function attachIsoToInstance(string $id, string $iso_id) : IsoStatus
	{
		return $this->createObject('instances/'.$id.'/iso/attach', new IsoStatus(), ['iso_id' => $iso_id]);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/detach-instance-iso
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return IsoStatus
	 */
	public function detachIsoFromInstance(string $id) : IsoStatus
	{
		try
		{
			$response = $this->getClientHandler()->post('instances/'.$id.'/iso/detach');
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to detach iso: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new IsoStatus();
		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/create-instance-backup-schedule
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $backup - BackupSchedule
	 * @throws InstanceException
	 * @return void
	 */
	public function setBackupSchedule(string $id, BackupSchedule $backup) : void
	{
		try
		{
			$this->getClientHandler()->post('instances/'.$id.'/backup-schedule', $backup->getInitializedProps());
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to setup backup schedule: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-backup-schedule
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return BackupSchedule
	 */
	public function getBackupSchedule(string $id) : BackupSchedule
	{
		return $this->getObject('instances/'.$id.'/backup-schedule', new BackupSchedule());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/restore-instance
	 * $snapshot_id or $backup_id must be specified. But they both cannot be specified.
	 * The actual response from the api returns an object. But its pointless as you have already fed in the parameters and it feeds it them back to you.
	 * The status doesn't mean anything to act off from the response. While your instance is restoring use getInstance to view its status.
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $snapshot_id - string|null
	 * @param $backup_id - string|null
	 * @throws InstanceException
	 * @return void
	 */
	public function restoreInstance(string $id, ?string $snapshot_id = null, ?string $backup_id = null) : void
	{
		if ($snapshot_id === null && $backup_id === null)
		{
			throw new InstanceException('1 of the following parameters must be specified. snapshot_id or backup_id');
		}
		else if ($snapshot_id !== null && $backup_id !== null)
		{
			throw new InstanceException('Only 1 parameter is allowed to be specified. Choose 1 snapshot_id or backup_id');
		}

		$params = [];
		if ($snapshot_id !== null)
		{
			$params['snapshot_id'] = $snapshot_id;
		}
		else if ($backup_id !== null)
		{
			$params['backup_id'] = $backup_id;
		}

		try
		{
			$this->getClientHandler()->post('instances/'.$id.'/restore', $params);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to restore instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	public function getIPv4Addresses(string $id)
	{

	}

	public function createIPv4Address(string $id)
	{

	}

	public function deleteIPv4Address(string $id, string $ip)
	{

	}

	public function createReverseIPv4Address(string $id)
	{

	}

	public function setDefaultIPv4ReverseDNSEntry(string $id, string $ip)
	{

	}

	public function getIPv6Addresses(string $id)
	{

	}

	public function createReverseIPv6Address(string $id)
	{

	}

	public function getReverseIPv6Addresses(string $id)
	{

	}

	public function deleteReverseIPv6Address(string $id, string $ipv6)
	{

	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-userdata
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws InstanceException
	 * @return string
	 */
	public function getUserData(string $id) : string
	{
		try
		{
			$response = $this->getClientHandler()->get('instances/'.$id.'/user-data');
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get user data: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return base64_decode(VultrUtil::decodeJSON((string)$response->getBody(), true)['user_data']['data']);
	}

	/**
	 * @see https://www.vultr.com/api/#operation/get-instance-upgrades
	 * @param $id - string - Instance Id - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $type - string - filter based on upgrade types.
	 * @throws InstanceException
	 * @return OperatingSystem|Application|VPSPlan[]
	 */
	public function getAvailableUpgrades(string $id, string $type = 'all') : array
	{
		try
		{
			$response = $this->getClientHandler()->get('instances/'.$id.'/upgrades', ['type' => $type]);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to get available upgrades: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$output = [];
		$upgrades = VultrUtil::decodeJSON((string)$response->getBody())->upgrades;

		foreach ($upgrades->os as $system)
		{
			$output[] = VultrUtil::mapObject($system, new OperatingSystem());
		}

		foreach ($upgrades->applications as $application)
		{
			$output[] = VultrUtil::mapObject($application, new Application());
		}

		foreach ($upgrades->plans as $vps_plan)
		{
			$plan = $this->getVultrClient()->plans->getPlan($vps_plan);
			if ($plan === null) continue; // Plan is no longer being offered.
			$output[] = $plan;
		}

		return $output;
	}

	// Private routines

	private function singleInstanceAction(string $action, string $id) : void
	{
		try
		{
			$this->getClientHandler()->post('instances/'.$id.'/'.$action);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to '.$action.' instance: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	private function multipleInstancesAction(string $action, array $ids) : void
	{
		try
		{
			$this->getClientHandler()->post('instances/'.$action, ['instance_ids' => $ids]);
		}
		catch (VultrClientException $e)
		{
			throw new InstanceException('Failed to '.$action.' instances: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}
