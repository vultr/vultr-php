<?php

namespace Vultr\VultrPhp\Applications;

use Vultr\VultrPhp\VultrException;
use Vultr\VultrPhp\VultrService;
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
		$client = $this->getClient();

		if ($options === null)
		{
			$options = new ListOptions(150);
		}

		try
		{
			$pagination = [
				'type'     => $filter,
				'per_page' => $options->getPerPage()
			];

			if ($options->getCurrentCursor() != '')
			{
				$pagination['cursor'] = $options->getCurrentCursor();
			}

			$response = $client->get('applications', $pagination);
		}
		catch (VultrException $e)
		{
			throw new ApplicationException('Failed to get applications: '.$e->getMessage(), $e->getHTTPCode());
		}

		$applications = [];
		try
		{
			$stdclass = json_decode($response->getBody()->getContents());
			$options->setTotal($stdclass->meta->total);
			$options->setNextCursor($stdclass->meta->links->next);
			$options->setPrevCursor($stdclass->meta->links->prev);
			foreach ($stdclass->applications as $object)
			{
				$applications[] = VultrUtil::mapObject($object, new Application());
			}
		}
		catch (Exception $e)
		{
			throw new ApplicationException('Failed to deserialize applications: '. $e->getMessage());
		}

		return $applications;
	}
}
