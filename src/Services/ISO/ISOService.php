<?php

namespace Vultr\VultrPhp\Services\ISO;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ISOService extends VultrService
{
	public function getISO(string $iso_id) : ISO
	{
		return $this->getObject('iso/'.$iso_id, new ISO());
	}

	public function getISOs(?ListOptions $options = null) : array
	{
		return $this->getListObjects('iso', new ISO(), $options);
	}

	public function getPublicISOs() : array
	{
		return $this->getListObjects('iso-public', new PublicISO());
	}

	public function createISO(string $url) : ISO
	{
		try
		{
			$response = $this->post('iso', [
				'url'         => $url,
			]);
		}
		catch (VultrServiceException $e)
		{
			throw new ISOException('Failed to create iso: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new ISO(), 'iso');
	}

	public function deleteISO(string $iso_id) : void
	{
		try
		{
			$this->delete('iso/'.$snapshot_id);
		}
		catch (VultrServiceException $e)
		{
			throw new ISOException('Failed to delete iso: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}
