<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\OperatingSystems;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class OperatingSystemService extends VultrService
{
	private static ?array $cache_operatingsystems = null;

	/**
	 * Get all operating systems that are available to be deployed.
	 *
	 * @param $options - ListOptions - Interact via reference.
	 * @throws OperatingSystemException
	 * @return OperatingSystem[]
	 */
	public function getOperatingSystems(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('os', new OperatingSystem(), $options);
	}

	/**
	 * Get a specific operating system that is deployable.
	 *
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
	 * Query and cache all operating systems from the vultr api.
	 *
	 * @param $override - bool - Depending on whether to requery the operating systems.
	 * @throws OperatingSystemException
	 * @return void
	 */
	public function cacheOperatingSystems(bool $override = false) : void
	{
		if (static::$cache_operatingsystems !== null && !$override) return;

		static::$cache_operatingsystems = [];
		$options = new ListOptions(500);
		while (true)
		{
			foreach ($this->getOperatingSystems($options) as $os)
			{
				static::$cache_operatingsystems[$os->getId()] = $os;
			}

			if ($options->getNextCursor() == '')
			{
				break;
			}
			$options->setCurrentCursor($options->getNextCursor());
		}
	}
}
