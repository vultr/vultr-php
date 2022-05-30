<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Vultr\VultrPhp\VultrClient;

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
		return VultrClient::create('TEST1234', new Client(['handler' => HandlerStack::create($mock)]));
	}
}
