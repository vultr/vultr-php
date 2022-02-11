<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class PlansTest extends VultrTest
{
	public function testGetPlan()
	{
		$this->markTestSkipped('Not implemented');
	}

	public function testGetVPSPlans()
	{
		$this->markTestSkipped('Not implemented');
	}

	public function testGetBMPlans()
	{
		$this->markTestSkipped('Not implemented');
	}
}
