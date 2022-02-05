<?php

namespace Vultr\VultrPhp\Util;

use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\CaseConversion;
use JsonMapper\Enums\TextNotation;
use stdClass;

class VultrUtil
{
	/**
	 * @param $stdClass - Raw class that comes from json_decode. This is what JsonMapper accepts.
	 * @param $model - A model that will be used to map the json response to the object.
	 * Model is passed in via reference, the model will immediately be reset before its mapped and cloned.
	 * @param $prop - The arching prop that has the contents that we will map the object to.
	 * Ex a Backup object will have "backup" : { 'id', etc etc}
	 * So in $prop we would specifiy "backup"
	 * @return ModelInterface
	 */
	public static function mapObject(stdClass $stdclass, ModelInterface $model, ?string $prop = null) : ModelInterface
	{
		$map = (new JsonMapperFactory())->bestFit();
		$map->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));

		$object = $stdclass;
		if ($prop !== null)
		{
			$object = $stdclass->$prop;
		}

		$model->resetObject();

		return $map->mapObject($object, clone $model);
	}
}
