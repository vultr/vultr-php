<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests;

use Error;
use PHPUnit\Framework\TestCase;
use Vultr\VultrPhp\Util\ModelInterface;

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

	protected function testListObject(ModelInterface $model, array $response_objects, array $spec_data) : void
	{
		$this->assertEquals($spec_data['meta']['total'], count($response_objects));
		foreach ($response_objects as $response_object)
		{
			$this->assertInstanceOf($model::class, $response_object);
			foreach ($spec_data[$response_object->getResponseListName()] as $object)
			{
				if ($object['id'] !== $response_object->getId()) continue;

				foreach ($response_object->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
				break;
			}
		}
	}
}
