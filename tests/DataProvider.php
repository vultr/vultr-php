<?php

namespace Vultr\VultrPhp\Tests;

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
}
