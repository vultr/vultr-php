<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Util;

use ReflectionClass;

/**
 * @ignore
 * Models need to implement camelcase properties.
 * These are converted from underscores from the api to camelCase for JSONMapper to assign them properly.
 * The reasoning why. Well I didn't feel like having to write custom Renames for each attribute.
 */
abstract class Model implements ModelInterface
{
	public function getModelExceptionClass() : string
	{
		return $this::class.'Exception';
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

			/**
			 * PHP Versions 8.0 and below will throw an error if checking if its initialized on protected props
			 * Even though they are a child of this class. Its dumb.
			 */
			if (version_compare(PHP_VERSION, '8.1', '<'))
			{
				$property->setAccessible(true);
			}

			if (!$property->isInitialized($this))
			{
				continue;
			}

			$method_name = 'get'.ucfirst($property->getName());
			$value = $this->$method_name();

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

	public function getInitializedProps() : array
	{
		$params = $this->toArray();
		$class = new ReflectionClass($this);
		foreach ($class->getProperties() as $property)
		{
			$attr = VultrUtil::convertCamelCaseToUnderscore($property->getName());
			if (!array_key_exists($attr, $params))
			{
				continue;
			}

			// These are optional values that may be set in a response.
			if ($property->hasDefaultValue() && $property->getDefaultValue() === $params[$attr])
			{
				unset($params[$attr]);
				continue;
			}
		}

		return $params;
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
			if ($property->hasDefaultValue())
			{
				$this->$name = $property->getDefaultValue();
				continue;
			}

			unset($this->$name);
		}
	}
}
