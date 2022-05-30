<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Util;

use JsonMapper\Enums\TextNotation;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\CaseConversion;
use Psr\Http\Message\ResponseInterface;
use stdClass;
use Throwable;
use Vultr\VultrPhp\VultrException;

class VultrUtil
{
	/**
	 * @param $stdClass - Raw class that comes from json_decode. This is what JsonMapper accepts.
	 * @param $model - A model that will be used to map the json response to the object.
	 * Model is passed in via reference, the model will immediately be reset before its mapped and cloned.
	 * @param $prop - The arching prop that has the contents that we will map the object to.
	 * Ex a Backup object will have "backup" : { 'id', etc etc}
	 * So in $prop we would specifiy "backup"
	 * @throws VultrException
	 * @return ModelInterface
	 */
	public static function mapObject(stdClass $stdclass, ModelInterface $model, ?string $prop = null) : ModelInterface
	{
		$object = $stdclass;
		if ($prop !== null)
		{
			$object = $stdclass->$prop;
		}

		if (!is_object($object))
		{
			throw new VultrException('Failed to map object for '.get_class($model).': Object is invalid. Percieved Object Data: '.var_export($object, true).'. Object Parameter: '.var_export($stdclass, true));
		}

		$mapped_object = $model;
		try
		{
			$map = (new JsonMapperFactory())->bestFit();
			$map->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));

			$model->resetObject();

			$mapped_object = $map->mapObject($object, clone $model);
		}
		catch (Throwable $e)
		{
			throw new VultrException('Failed to map object for '.get_class($model).': '.$e->getMessage(), VultrException::DEFAULT_CODE, null, $e);
		}

		return $mapped_object;
	}

	/**
	 * @param $json - string - Raw JSON
	 * @param $model - A model that will be used to map the json response to the object.
	 * Model is passed in via reference, the model will immediately be reset before its mapped and cloned.
	 * @param $prop - The arching prop that has the contents that we will map the object to.
	 * Ex a Backup object will have "backup" : { 'id', etc etc}
	 * So in $prop we would specifiy "backup"
	 * @throws VultrException
	 * @return ModelInterface
	 */
	public static function convertJSONToObject(string $json, ModelInterface $model, ?string $prop = null) : ModelInterface
	{
		$class_name = get_class($model);
		$std_class = json_decode($json);
		if (!is_object($std_class))
		{
			throw new VultrException('Failed to deserialize json for '.$class_name.' object: '.json_last_error_msg());
		}

		try
		{
			$object = self::mapObject($std_class, $model, $prop);
		}
		catch (Throwable $e)
		{
			throw new VultrException('Failed to deserialize '.$class_name.' object: '.$e->getMessage(), VultrException::DEFAULT_CODE, null, $e);
		}

		return $object;
	}

	/**
	 * @param $camelCase - string - a camel cased string that will be converted to underscore notation
	 * @param $lowercase - bool - whether the string will be all lowercase or not.
	 * @return string
	 */
	public static function convertCamelCaseToUnderscore(string $camelCase, bool $lowercase = true) : string
	{
		$underscored = (string)preg_replace('/(?<!^)[A-Z]/', '_$0', $camelCase);
		return $lowercase ? strtolower($underscored) : $underscored;
	}

	/**
	 * Get Status Code level
	 * @return int
	 */
	public static function getLevel(ResponseInterface $response) : int
	{
		return (int) floor($response->getStatusCode() / 100);
	}
}
