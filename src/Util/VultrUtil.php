<?php

namespace Vultr\VultrPhp\Util;

use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\CaseConversion;
use JsonMapper\Enums\TextNotation;
use stdClass;

class VultrUtil
{
	public static function mapObject(stdClass $stdclass, ModelInterface $model, ?string $prop = null) : ModelInterface
	{
		$map = (new JsonMapperFactory())->bestFit();
		$map->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));

		$object = $stdclass;
		if ($prop !== null)
		{
			$object = $stdclass->$prop;
		}

		return $map->mapObject($object, $model);
	}
}
