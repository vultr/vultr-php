<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use RuntimeException;
use stdClass;
use Vultr\VultrPhp\Tests\Data\ModelOptionsData;
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
		$this->assertEmpty($object->getUpdateParams());
		foreach ($object->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $test['test'][$attr]);
		}

		$this->expectException(VultrException::class);
		$json = 'asdasfasfs';
		VultrUtil::convertJSONToObject($json, new VultrUtilData());
	}

	public function testDecodeJSON()
	{
		$json = json_encode([
			'id' => 1,
			'value1' => 'addasdasdfasf',
			'value2' => 69,
			'value3' => 4.20,
			'value4' => ['spaghetti', 'bolognese'],
			'value5' => false
		]);

		$object = VultrUtil::decodeJSON($json, false);

		$this->assertInstanceOf(stdClass::class, $object);

		$array = VultrUtil::decodeJSON($json, true);
		$this->assertIsArray($array);

		$this->expectException(VultrException::class);
		VultrUtil::decodeJSON('jsadngfasjdfg dsajvndsagfjnsadfjnsadfjasdfn ssadfjsadfjnsdaf');
	}

	public function testConvertCamelCaseToUnderscore()
	{
		$this->assertEquals('hello_world', VultrUtil::convertCamelCaseToUnderscore('helloWorld'));
		$this->assertEquals('hello_World', VultrUtil::convertCamelCaseToUnderscore('helloWorld', false));
	}

	public function testConvertUnderscoreToCamelCase()
	{
		$this->assertEquals('helloWorld', VultrUtil::convertUnderscoreToCamelCase('hello_world'));
	}

	public function testModelOptions()
	{
		$options = new ModelOptionsData();

		try
		{
			$options->AAAasfe(12345);
		}
		catch (RuntimeException $e)
		{
			$this->assertStringContainsString('Call to undefined method', $e->getMessage());
			$this->assertStringNotContainsString('prop', $e->getMessage());
		}

		try
		{
			$options->getHelloWorld();
		}
		catch (RuntimeException $e)
		{
			$this->assertStringContainsString('Call to undefined method prop', $e->getMessage());
		}

		try
		{
			$options->dudeString();
		}
		catch (RuntimeException $e)
		{
			$this->assertStringContainsString('Call to undefined action', $e->getMessage());
		}

		$this->assertNotEquals($options->getArray(), $options->withArray(['fu']));
		$this->assertNotEquals($options->getInt(), $options->withInt(5));
		$this->assertNotEquals($options->getString(), $options->withString('aaaaa'));

		$old = $options->getInt();
		$options->setInt(55);
		$this->assertNotEquals($old, $options->getInt());
	}
}
