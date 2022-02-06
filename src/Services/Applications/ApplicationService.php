<?php

namespace Vultr\VultrPhp\Services\Applications;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class ApplicationService extends VultrService
{
	public const FILTER_ALL = 'all';
	public const FILTER_MARKETPLACE = 'marketplace';
	public const FILTER_ONE_CLICK = 'one-click';

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
}
