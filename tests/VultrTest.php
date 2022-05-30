<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests;

use Error;
use PHPUnit\Framework\TestCase;

class VultrTest extends TestCase
{
	private ?DataProviderInterface $provider = null;

	public function __construct(string $name = '', array $data = [], string $dataName = '')
	{
		$this->initDataProvider();
		parent::__construct($name, $data, $dataName);
	}

	private function initDataProvider() : void
	{
		$class = str_replace(['Vultr\VultrPhp\Tests\Suite\\', 'Test'], '', get_class($this)).'Data';
		$full_path = '\Vultr\VultrPhp\Tests\Data\\'.$class;
		try
		{
			$this->provider = new $full_path();
		}
		catch (Error)
		{
			$this->provider = null;
		}
	}

	protected function getDataProvider() : ?DataProviderInterface
	{
		return $this->provider;
	}

	protected function mapRegions(array $regions) : array
	{
		$region_map = [];
		foreach ($regions as $region)
		{
			$region_map[$region['id']] = $region;
		}
		return $region_map;
	}
}
