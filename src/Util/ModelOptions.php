<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Util;

use ReflectionClass;
use RuntimeException;

abstract class ModelOptions
{
	private array $prop_map;

	public function __construct()
	{
		$reflection = new ReflectionClass($this::class);

		foreach ($reflection->getProperties() as $property)
		{
			$this->prop_map[$property->getName()] = (string)$property->getType();
		}
	}

	public function __call($name, $args) : mixed
	{
		if (!preg_match('/^([a-z]{3,4})(.*)$/', $name, $match))
		{
			throw new RuntimeException('Call to undefined method '.$this::class.'::'.$name);
		}

		$prop = VultrUtil::convertCamelCaseToUnderscore($match[2]);
		$action = $match[1];
		if (!isset($this->prop_map[$prop]))
		{
			throw new RuntimeException('Call to undefined method prop '.$this::class.'::'.$name);
		}

		switch ($action)
		{
			case 'get':
			return $this->$prop;

			case 'set':
				$this->$prop = $args[0];
			return null;

			case 'with':
				$object = clone $this;
				$object->$prop = $args[0];
			return $object;

			default:
			throw new RuntimeException('Call to undefined action `'.$action.'` in '.$this::class.'::'.$name);
		}
	}

	public function getPayloadParams() : array
	{
		$output = [];
		foreach (array_keys($this->prop_map) as $key)
		{
			if ($this->$key === null) continue;

			$value = $this->$key;
			if ($value instanceof Model)
			{
				$value = $value->toArray();
			}

			$output[$key] = $value;
		}

		return $output;
	}
}
