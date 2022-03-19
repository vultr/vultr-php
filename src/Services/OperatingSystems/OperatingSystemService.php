<?php

namespace Vultr\VultrPhp\Services\OperatingSystems;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Util\ListOptions;

class OperatingSystemService extends VultrService
{
	private static ?array $cache_operatingsystems = null;

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws OperatingSystemException
	 * @return OperatingSystem[]
	 */
	public function getOperatingSystems(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('os', new OperatingSystem(), $options);
	}

	/**
	 * @param $id - string - Ex 124 - OS id.
	 * @throws OperatingSystemException
	 * @return OperatingSystem|null
	 */
	public function getOperatingSystem(int $id) : ?OperatingSystem
	{
		$this->cacheOperatingSystems();
		return static::$cache_operatingsystems[$id] ?? null;
	}

	/**
	 * @param $override - bool - Depending on whether to requery the operating systems.
	 * @throws OperatingSystemException
	 * @return void
	 */
	public function cacheOperatingSystems(bool $override = false) : void
	{
		if (static::$cache_operatingsystems !== null && !$override) return;

		static::$cache_operatingsystems = [];
		$options = new ListOptions(500);
		foreach ($this->getOperatingSystems($options) as $os)
		{
			static::$cache_operatingsystems[$os->getId()] = $os;
		}
	}
}
