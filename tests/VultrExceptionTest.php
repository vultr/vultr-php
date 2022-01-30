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
	}
}
