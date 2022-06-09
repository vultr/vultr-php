<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;
use Vultr\VultrPhp\VultrClient;

abstract class DataProvider implements DataProviderInterface
{
	public function getData($param = null) : array
	{
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
		$function = str_replace('test', 'data', $backtrace['function']);
		if (method_exists($this, $function))
		{
			if ($param !== null)
			{
				return $this->$function($param);
			}
			return $this->$function();
		}

		$folder = str_replace('data', '', str_replace('vultr\vultrphp\tests\data\\', '', strtolower($this::class)));
		$file = __DIR__.'/json_responses/'.$folder.'/'.$function.'.json';

		if (!file_exists($file))
		{
			throw new InvalidArgumentException('Please specify either a data provider function '.$backtrace['class'].'::'.$function.' or add the json response for the test at '.$file);
		}
		return json_decode(file_get_contents($file), true);
	}

	public function createClientHandler(array $requests, ?MockHandler &$mock = null) : VultrClient
	{
		$mock = new MockHandler($requests);
		return VultrClient::create('TEST1234', new Client(['handler' => HandlerStack::create($mock)]));
	}
}
