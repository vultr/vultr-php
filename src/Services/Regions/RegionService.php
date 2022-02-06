<?php

namespace Vultr\VultrPhp\Services\Regions;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class RegionService extends VultrService
{
	/**
	* @param $per_page
	* @param $cursor
	*/
	public function getRegions(?ListOptions &$options = null) : array
	{
		$regions = [];
		try
		{
			if ($options === null)
			{
				$options = new ListOptions(50);
			}
			$regions = $this->list('regions', new Region(), $options);
		}
		catch (VultrServiceException $e)
		{
			throw new RegionException("Failed to get regions: " .$e->getMessage(), $e->getHTTPCode());
		}

		return $regions;
	}
}
