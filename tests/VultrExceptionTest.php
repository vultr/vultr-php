<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrException;
use PHPUnit\Framework\TestCase;

class VultrExceptionTest extends TestCase
{
	public function testDefaultException()
	{
		try
		{
			throw new VultrException('Test Default');
		}
		catch (VultrException $e)
		{
			$this->assertEquals(VultrException::DEFAULT_CODE, $e->getCode());
			$this->assertEquals(null, $e->getHTTPCode());
		}

		$test_code = 301;
		$test_http = 500;
		try
		{
			throw new VultrException('Test Default 2', $test_code, $test_http, null);
		}
		catch (VultrException $e)
		{
			$this->assertEquals($test_code, $e->getCode());
			$this->assertEquals($test_http, $e->getHTTPCode());
		}
	}
}
