<?php

namespace Vultr\VultrPhp\Util;

abstract class Model implements ModelInterface
{
	public function toArray() : array
	{
		return [];
	}
}
