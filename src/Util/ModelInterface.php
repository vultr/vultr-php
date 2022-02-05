<?php

namespace Vultr\VultrPhp\Util;

interface ModelInterface
{
	public function getResponseListName() : string;
	public function toArray() : array;
}
