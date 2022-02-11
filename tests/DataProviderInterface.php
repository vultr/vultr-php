<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;

interface DataProviderInterface
{
	public function getData() : array;

	public function createClientHandler(array $requests) : VultrClient;
}
