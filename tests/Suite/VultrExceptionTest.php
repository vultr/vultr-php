<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\VultrException;

class VultrExceptionTest extends VultrTest
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
			$this->assertNull($e->getHTTPCode());
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

	public function testVultrServiceException()
	{
		try
		{
			throw new VultrServiceException('Test');
		}
		catch (VultrServiceException $e)
		{
			$this->assertEquals(VultrException::SERVICE_CODE, $e->getCode());
			$this->assertNull($e->getHTTPCode());
		}

		$test_code = 302;
		$test_http = 400;
		try
		{
			throw new VultrServiceException('Test', $test_code, $test_http);
		}
		catch (VultrServiceException $e)
		{
			$this->assertEquals($test_code, $e->getCode());
			$this->assertEquals($test_http, $e->getHTTPCode());
		}
	}
}
