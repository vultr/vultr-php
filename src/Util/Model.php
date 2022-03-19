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
	public function getModelExceptionClass() : string
	{
		return get_class($this).'Exception';
	}

	/**
	 * Flat array to specify array props that will be checked on whether they should be updated or not.
	 * @return array
	 */
	public function getUpdateParams() : array
	{
		return [];
	}

	/**
	 * Get the list name of a model.
	 * For instance during a list api call, objects will be wrapped in a parent json.
	 * This allows us to conform with the api and target the specific element without statically defining them.
	 */
	public function getResponseListName() : string
	{
		return rtrim($this->getResponseName(), 's').'s';
	}

	/**
	 * Get the wrapped response of an individual object
	 * This allows us to conform with the api and target the specific element without statically defining them.
	 */
	public function getResponseName() : string
	{
		$classname = get_class($this);
		if ($pos = strrpos($classname, '\\'))
		{
			$classname = substr($classname, $pos + 1);
		}
		return VultrUtil::convertCamelCaseToUnderscore($classname);
	}

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
			$underscore_prop = VultrUtil::convertCamelCaseToUnderscore($property->getName());

			$nulled_value = '';
			switch ($property->getType()->getName())
			{
				case 'string':
					$nulled_value = '';
				break;

				case 'float':
				case 'int':
					$nulled_value = 0;
				break;

				case 'array':
					$nulled_value = [];
				break;

				case 'bool':
					$nulled_value = false;
				break;
			}

			$value = $nulled_value;
			/**
			 * PHP Versions 8.0 and below will throw an error if checking if its initialized on protected props
			 * Even though they are a child of this class. Its dumb.
			 */
			if (version_compare(PHP_VERSION, '8.1', '<'))
			{
				$property->setAccessible(true);
			}

			if ($property->isInitialized($this))
			{
				$method_name = 'get'.ucfirst($property->getName());
				$value = $this->$method_name();
			}

			$array[$underscore_prop] =  $value;
		}

		return $array;
	}

	public function getUpdateArray() : array
	{
		$update = [];
		$attributes = $this->toArray();
		foreach ($this->getUpdateParams() as $param)
		{
			if (empty($attributes[$param])) continue;

			$update[$param] = $attributes[$param];
		}

		return $update;
	}

	/**
	 * Reset properties of the object to uninitialized state.
	 */
	public function resetObject() : void
	{
		$reflection = new ReflectionClass($this);
		foreach ($reflection->getProperties() as $property)
		{
			$name = $property->getName();

			unset($this->$name);
		}
	}
}
