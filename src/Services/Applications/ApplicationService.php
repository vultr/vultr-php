<?php

namespace Vultr\VultrPhp\Services\Applications;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class ApplicationService extends VultrService
{
	public const FILTER_ALL = 'all';
	public const FILTER_MARKETPLACE = 'marketplace';
	public const FILTER_ONE_CLICK = 'one-click';

	private static ?array $cache_applications = null;

	/**
	 * @param $filter - ENUM('all', 'marketplace', 'one-click')
	 * @param $per_page - MAX 500
	 * @param $cursor
	 */
	public function getApplications(string $filter = self::FILTER_ALL, ?ListOptions &$options = null) : array
	{
		$applications = [];
		try
		{
			if ($options === null)
			{
				$options = new ListOptions(150);
			}
			$applications = $this->list('applications', new Application(), $options, ['type' => $filter]);
		}
		catch (VultrServiceException $e)
		{
			throw new ApplicationException('Failed to get applications: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $applications;
	}

	/**
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
		foreach ($this->getApplications(self::FILTER_ALL) as $app)
		{
			static::$cache_applications[$app->getId()] = $app;
		}
	}
}
