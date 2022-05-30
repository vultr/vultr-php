<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Util;

interface ModelInterface
{
	public function getResponseName() : string;
	public function getResponseListName() : string;
	public function toArray() : array;
	public function getUpdateArray() : array;
	public function getUpdateParams() : array;
	public function resetObject() : void;
	public function getModelExceptionClass() : string;
}
