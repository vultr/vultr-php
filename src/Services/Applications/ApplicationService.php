<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Applications;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ApplicationService extends VultrService
{
	public const FILTER_ALL = 'all';
	public const FILTER_MARKETPLACE = 'marketplace';
	public const FILTER_ONE_CLICK = 'one-click';

	private static ?array $cache_applications = null;

	/**
	 * Get a list of all available application images.
	 * @param $filter - ENUM('all', 'marketplace', 'one-click')
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws ApplicationException
	 * @return Application[]
	 */
	public function getApplications(string $filter = self::FILTER_ALL, ?ListOptions &$options = null) : array
	{
		if ($options === null)
		{
			$options = new ListOptions(150);
		}
		return $this->getListObjects('applications', new Application(), $options, ['type' => $filter]);
	}

	/**
	 * Get a specific application object based on the app_id.
	 * @param $id - int - Application id, whether one click or marketplace app.
	 * @throws ApplicationException
	 * @return Application|null
	 */
	public function getApplication(int $id) : ?Application
	{
		$this->cacheApplications();
		return static::$cache_applications[$id] ?? null;
	}

	/**
	 * @param $override - bool - Depending on whether to requery the applications.
	 * @throws ApplicationException
	 * @return void
	 */
	public function cacheApplications(bool $override = false) : void
	{
		if (static::$cache_applications !== null && !$override) return;

		static::$cache_applications = [];
		$options = new ListOptions(500);
		while (true)
		{
			foreach ($this->getApplications(self::FILTER_ALL, $options) as $app)
			{
				static::$cache_applications[$app->getId()] = $app;
			}

			if ($options->getNextCursor() == '')
			{
				break;
			}
			$options->setCurrentCursor($options->getNextCursor());
		}
	}
}
