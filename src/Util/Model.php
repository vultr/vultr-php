<?php

namespace Vultr\VultrPhp\Util;

use ReflectionClass;

/**
 * Models need to implement camelcase properties.
 * These are converted from underscores from the api to camelCase for JSONMapper to assign them properly.
 * The reasoning why. Well I didn't feel like having to write custom Renames for each attribute.
 */
abstract class Model implements ModelInterface
{
	/**
	 * Will output an array to match the json that we had received originally from the response which is in object form.
	 */
	public function toArray() : array
	{
		$reflection = new ReflectionClass($this);

		$array = [];
		foreach ($reflection->getProperties() as $property)
		{
			// Convert our camelcased properties to underscores.
			$underscore_prop = strtolower((string) \preg_replace('/(?<!^)[A-Z]/', '_$0', $property->getName()));

			$method_name = 'get'.ucfirst($property->getName());
			$array[$underscore_prop] = $this->$method_name();
		}

		return $array;
	}
}
