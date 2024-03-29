<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Services\Applications\Application;
use Vultr\VultrPhp\Services\OperatingSystems\OperatingSystem;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

/**
 * Baremetal service handler, for bare-metals endpoints.
 *
 * @see https://www.vultr.com/api/#tag/baremetal
 */
class BareMetalService extends VultrService
{
	/**
	 * List all Bare Metal instances in your account.
	 * @see https://www.vultr.com/api/#operation/list-baremetals
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BareMetalException
	 * @return BareMetal[]
	 */
	public function getBareMetals(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('bare-metals', new BareMetal(), $options);
	}

	/**
	 * Get information for a Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/get-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function getBareMetal(string $id) : BareMetal
	{
		return $this->getObject('bare-metals/'.$id, new BareMetal());
	}

	/**
	 * Delete a Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/delete-baremetal
	 * @throws BareMetalException
	 * @return void
	 */
	public function deleteBareMetal(string $id) : void
	{
		$this->deleteObject('bare-metals/'.$id, new BareMetal());
	}

	/**
	 * Create a new Bare Metal instance based on BareMetalCreate
	 * @see BareMetalCreate
	 * @see https://www.vultr.com/api/#operation/create-baremetal
	 * @param $payload - BareMetalCreate
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function createBareMetal(BareMetalCreate $payload) : BareMetal
	{
		return $this->createObject('bare-metals', new BareMetal(), $payload->getPayloadParams());
	}

	/**
	 * Update a Bare Metal instance. All attributes are optional in BareMetalUpdate. If not set the attributes will not be sent to the API.
	 * @see https://www.vultr.com/api/#operation/update-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $payload - BareMetalUpdate
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function updateBareMetal(string $id, BareMetalUpdate $payload) : BareMetal
	{
		$client = $this->getClientHandler();

		try
		{
			$response = $client->patch('bare-metals/'.$id, $payload->getPayloadParams());
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to update baremetal server: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$model = new BareMetal();

		return VultrUtil::convertJSONToObject((string)$response->getBody(), $model, $model->getResponseName());
	}

	/**
	 * Get all IPv4 information for the Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/get-ipv4-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @throws VultrException
	 * @return BareMetalIPv4Info[]
	 */
	public function getIPv4Addresses(string $id) : array
	{
		return $this->getAddressInfo(new BareMetalIPv4Info(), 'bare-metals/'.$id.'/ipv4', $id);
	}

	/**
	 * Get all IPv6 information for the Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/get-ipv6-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @throws VultrException
	 * @return BareMetalIPv6Info[]
	 */
	public function getIPv6Addresses(string $id) : array
	{
		return $this->getAddressInfo(new BareMetalIPv6Info(), 'bare-metals/'.$id.'/ipv6', $id);
	}

	private function getAddressInfo(ModelInterface $model, string $uri, string $id) : array
	{
		$client = $this->getClientHandler();

		try
		{
			$response = $client->get($uri);
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to get baremetal address information: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$objects = [];
		$decode = VultrUtil::decodeJSON((string)$response->getBody());
		$response_name = $model->getResponseListName();
		foreach ($decode->$response_name as $info)
		{
			$object = VultrUtil::mapObject($info, $model);
			$objects[] = $object;
		}
		return $objects;
	}

	/**
	 * @see https://www.vultr.com/api/#operation/start-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return void
	 */
	public function startBareMetal(string $id) : void
	{
		$this->singleServerAction('start', $id);
	}

	/**
	 * Start the Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/start-bare-metals
	 * @param $ids - array - Example: [cb676a46-66fd-4dfb-b839-443f2e6c0b60, cb676a46-66fd-4dfb-b839-443f2e6c0b65]
	 * @throws BareMetalException
	 * @return void
	 */
	public function startBareMetals(array $ids) : void
	{
		$this->multipleServersAction('start', $ids);
	}

	/**
	 * Reboot the Bare Metal instance.
	 * @see https://www.vultr.com/api/#operation/reboot-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return void
	 */
	public function rebootBareMetal(string $id) : void
	{
		$this->singleServerAction('reboot', $id);
	}

	/**
	 * Reboot multiple Bare Metal instances with 1 api call.
	 * @see https://www.vultr.com/api/#operation/reboot-bare-metals
	 * @param $ids - array - Example: [cb676a46-66fd-4dfb-b839-443f2e6c0b60, cb676a46-66fd-4dfb-b839-443f2e6c0b65]
	 * @throws BareMetalException
	 * @return void
	 */
	public function rebootBareMetals(array $ids) : void
	{
		$this->multipleServersAction('reboot', $ids);
	}

	/**
	 * Reinstall the Bare Metal instance. This action usually takes a few seconds to complete.
	 * @see https://www.vultr.com/api/#operation/reinstall-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return BareMetal
	 */
	public function reinstallBareMetal(string $id) : BareMetal
	{
		return $this->createObject('bare-metals/'.$id.'/reinstall', new BareMetal(), []);
	}

	/**
	 * Halt the Bare Metal instance. The machine will remain off till started again.
	 * @see https://www.vultr.com/api/#operation/halt-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @return void
	 */
	public function haltBareMetal(string $id) : void
	{
		$this->singleServerAction('halt', $id);
	}

	/**
	 * Halt multiple Bare Metal instances. The machines will remain off till started again.
	 * @see https://www.vultr.com/api/#operation/halt-baremetals
	 * @param $ids - array - Example: [cb676a46-66fd-4dfb-b839-443f2e6c0b60, cb676a46-66fd-4dfb-b839-443f2e6c0b65]
	 * @throws BareMetalException
	 * @return void
	 */
	public function haltBareMetals(array $ids) : void
	{
		$this->multipleServersAction('halt', $ids);
	}

	private function singleServerAction(string $action, string $id) : void
	{
		try
		{
			$this->getClientHandler()->post('bare-metals/'.$id.'/'.$action);
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to '.$action.' baremetal server: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	private function multipleServersAction(string $action, array $ids) : void
	{
		try
		{
			$this->getClientHandler()->post('bare-metals/'.$action, ['baremetal_ids' => $ids]);
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to '.$action.' baremetal servers: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * Get bandwidth information for the Bare Metal instance
	 *
	 * The structure of the array will follow this format.
	 * ['2022-11-05' => ['incoming_bytes' => 234523452352, 'outgoing_bytes' => 132432423]]
	 *
	 * @see https://www.vultr.com/api/#operation/get-bandwidth-baremetal
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @throws VultrException
	 * @return array
	 */
	public function getBandwidth(string $id) : array
	{
		try
		{
			$response = $this->getClientHandler()->get('bare-metals/'.$id.'/bandwidth');
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to get bandwidth for baremetal server: '.$e->getMessage(), $e->getHTTPCode(), $e);
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
	 * Get the user-supplied, which is decoded for you from base64 that the api returns.
	 *
	 * @see https://www.vultr.com/api/#operation/get-bare-metal-userdata
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @throws VultrException
	 * @return string
	 */
	public function getUserData(string $id) : string
	{
		try
		{
			$response = $this->getClientHandler()->get('bare-metals/'.$id.'/user-data');
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to get user data: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return base64_decode(VultrUtil::decodeJSON((string)$response->getBody(), true)['user_data']['data']);
	}

	/**
	 * Get available upgrades for a Bare Metal instance.
	 *
	 * @see https://www.vultr.com/api/#operation/get-bare-metals-upgrades
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @param $type - string - filter based on upgrade types.
	 * @throws BareMetalException
	 * @return OperatingSystem|Application[]
	 */
	public function getAvailableUpgrades(string $id, string $type = 'all') : array
	{
		try
		{
			$response = $this->getClientHandler()->get('bare-metals/'.$id.'/upgrades', ['type' => $type]);
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to get available upgrades: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		$output = [];
		$application = new Application();
		$os = new OperatingSystem();
		foreach (VultrUtil::decodeJSON((string)$response->getBody())->upgrades as $type => $upgrades)
		{
			foreach ($upgrades as $upgrade)
			{
				$output[] = VultrUtil::mapObject($upgrade, $type === 'os' ? clone $os : clone $application);
			}
		}

		return $output;
	}

	/**
	 * Get the VNC URL for a Bare Metal instance. Which can be used to access the console of the machine.
	 *
	 * @see https://www.vultr.com/api/#operation/get-bare-metal-vnc
	 * @param $id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws BareMetalException
	 * @throws VultrException
	 * @return string
	 */
	public function getVNCUrl(string $id) : string
	{
		try
		{
			$response = $this->getClientHandler()->get('bare-metals/'.$id.'/vnc');
		}
		catch (VultrClientException $e)
		{
			throw new BareMetalException('Failed to get vnc url: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::decodeJSON((string)$response->getBody(), true)['vnc']['url'];
	}
}
