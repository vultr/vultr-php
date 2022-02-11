<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

abstract class DataProvider implements DataProviderInterface
{
	public function getData($param = null) : array
	{
		$function = str_replace('test', 'data', debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']);

		if ($param !== null)
		{
			return $this->$function($param);
		}
		return $this->$function();
	}

	public function createClientHandler(array $requests)
	{
		return VultrClient::create('TEST1234', ['handler' => HandlerStack::create(new MockHandler($requests))]);
	}
}
