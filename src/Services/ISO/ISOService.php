<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ISO;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ISOService extends VultrService
{
	public function getISO(string $iso_id) : ISO
	{
		return $this->getObject('iso/'.$iso_id, new ISO());
	}

	public function getISOs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('iso', new ISO(), $options);
	}

	public function getPublicISOs(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('iso-public', new PublicISO(), $options);
	}

	public function createISO(string $url) : ISO
	{
		return $this->createObject('iso', new ISO(), ['url' => $url]);
	}

	public function deleteISO(string $iso_id) : void
	{
		$this->deleteObject('iso/'.$iso_id, new ISO());
	}
}
