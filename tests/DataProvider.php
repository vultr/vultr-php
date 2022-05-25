<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;

use GuzzleHttp\Client;
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

	public function createClientHandler(array $requests, ?MockHandler &$mock = null) : VultrClient
	{
		$mock = new MockHandler($requests);
		return VultrClient::create(new Client(['handler' => HandlerStack::create($mock)]), 'TEST1234');
	}
}
