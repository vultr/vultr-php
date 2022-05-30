<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use stdClass;
use Vultr\VultrPhp\Tests\Data\VultrUtilData;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrException;

class VultrUtilTest extends VultrTest
{
	public function testMapObject()
	{
		$std_class = new stdClass();
		$std_class->id = 1;
		$std_class->value1 = 'addddsdsd';
		$std_class->value2 = 69;
		$std_class->value3 = 4.20;
		$std_class->value4 = ['spaghetti', 'more spaghetti'];
		$std_class->value5 = false;

		$object = VultrUtil::mapObject($std_class, new VultrUtilData());

		foreach ($object->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $std_class->$attr);
		}
	}

	public function testConvertJSONToObject()
	{
		$array = [
			'id' => 1,
			'value1' => 'addasdasdfasf',
			'value2' => 69,
			'value3' => 4.20,
			'value4' => ['spaghetti', 'bolognese'],
			'value5' => false
		];
		$json = json_encode($array);

		$object = VultrUtil::convertJSONToObject(json_encode($array), new VultrUtilData());
		foreach ($object->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $array[$attr]);
		}

		$test = [];
		$test['test'] = $array;
		$object = VultrUtil::convertJSONToObject(json_encode($test), new VultrUtilData(), 'test');
		foreach ($object->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $test['test'][$attr]);
		}

		$this->expectException(VultrException::class);
		$json = 'asdasfasfs';
		VultrUtil::convertJSONToObject($json, new VultrUtilData());
	}
}
