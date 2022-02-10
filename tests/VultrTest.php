<?php

namespace Vultr\VultrPhp\Tests;

use Error;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class VultrTest extends TestCase
{
	private ?DataProviderInterface $provider = null;

	public function __construct()
	{
		$this->initDataProvider();
		parent::__construct();
	}

	private function initDataProvider() : void
	{
		$class = str_replace(['Vultr\VultrPhp\Tests\Suite\\', 'Test'], '', get_class($this)).'Data';
		$full_path = '\Vultr\VultrPhp\Tests\Data\\'.$class;
		try
		{
			$this->provider = new $full_path();
		}
		catch (Error)
		{
			$this->provider = null;
		}
	}

	public function getDataProvider() : ?DataProviderInterface
	{
		return $this->provider;
	}
}
