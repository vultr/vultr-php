<?php

namespace Vultr\VultrPhp\Util;

use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\CaseConversion;
use JsonMapper\Enums\TextNotation;
use GuzzleHttp\Psr7\Response;

class VultrUtil
{
	public static function mapObject(Response $response, ModelInterface $model, ?string $prop = null) : ModelInterface
	{
		$map = (new JsonMapperFactory())->bestFit();
		$map->push(new CaseConversion(TextNotation::UNDERSCORE(), TextNotation::CAMEL_CASE()));

		$stdclass = json_decode($response->getBody()->getContents());

		$object = $stdclass;
		if ($prop !== null)
		{
			$object = $stdclass->$prop;
		}

		return $map->mapObject($object, $model);
	}
}
