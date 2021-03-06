<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests;

use Error;
use PHPUnit\Framework\TestCase;
use Vultr\VultrPhp\Util\ListOptions;
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

	protected function testListObject(ModelInterface $model, array $response_objects, array $spec_data, string $key = 'id', string $response_func = 'getId') : void
	{
		$this->assertEquals($spec_data['meta']['total'], count($response_objects));
		foreach ($response_objects as $response_object)
		{
			foreach ($spec_data[$response_object->getResponseListName()] as $object)
			{
				if ($object[$key] !== $response_object->$response_func()) continue;

				$this->testObject($model, $response_object, $object);
				break;
			}
		}
	}

	protected function testGetObject(ModelInterface $model, ModelInterface $response_object, array $spec_data) : void
	{
		$this->testObject($model, $response_object, $spec_data[$response_object->getResponseName()]);
	}

	protected function testObject(ModelInterface $model, ModelInterface $response_object, array $spec_data) : void
	{
		$this->assertInstanceOf($model::class, $response_object);
		$array = $response_object->toArray();
		foreach ($array as $attr => $value)
		{
			if ($value === null && !array_key_exists($attr, $spec_data)) continue;

			$this->assertEquals($value, $spec_data[$attr], "Attribute {$attr} failed to meet comparison against spec.");
		}

		foreach (array_keys($spec_data) as $attr)
		{
			$this->assertTrue(array_key_exists($attr, $array), "Attribute {$attr} failed to exist in toArray of the response object.");
		}
	}

	protected function createListOptions() : ListOptions
	{
		return new ListOptions(100);
	}
}
