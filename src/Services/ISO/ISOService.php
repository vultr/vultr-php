<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ISO;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

/**
 * ISO service handler, for all iso endpoints.
 *
 * @see https://www.vultr.com/api/#tag/iso
 */
class ISOService extends VultrService
{
	/**
	 * Get the ISO on the account.
	 *
	 * @param $iso_id - string
	 * @throws ISOException
	 * @return ISO
	 */
	public function getISO(string $iso_id) : ISO
	{
		return $this->getObject('iso/'.$iso_id, new ISO());
	}

	/**
	 * Get ISOS on the account.
	 *
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ISOException
	 * @return ISO[]
	 */
	public function getISOs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('iso', new ISO(), $options);
	}

	/**
	 * Get all publically available isos regardless of account.
	 *
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ISOException
	 * @return PublicISO[]
	 */
	public function getPublicISOs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('iso-public', new PublicISO(), $options);
	}

	/**
	 * Create an ISO from a url endpoint
	 *
	 * @param $url - string - Example: https://youramazing.com/url.iso
	 * @throws ISOException
	 * @return ISO
	 */
	public function createISO(string $url) : ISO
	{
		return $this->createObject('iso', new ISO(), ['url' => $url]);
	}

	/**
	 * Delete an ISO on the account.
	 *
	 * @param $iso_id - string
	 * @throws ISOException
	 * @return void
	 */
	public function deleteISO(string $iso_id) : void
	{
		$this->deleteObject('iso/'.$iso_id, new ISO());
	}
}
