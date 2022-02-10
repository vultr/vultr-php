<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Services\Applications\Application;
use Vultr\VultrPhp\Services\Applications\ApplicationService;
use Vultr\VultrPhp\Services\Applications\ApplicationException;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class ApplicationsTest extends VultrTest
{
	public function testGetApplications()
	{
		$data = $this->getDataProvider()->getData();

		// Filter one-click
		$data2 = $data;
		unset($data2['applications'][1]);
		$data2['meta']['total'] = 1;

		// Filter marketplace
		$data3 = $data;
		unset($data3['applications'][0]);
		$data3['meta']['total'] = 1;

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data2)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data3)),
			new RequestException('This is an exception', new Request('GET', 'applications'), new Response(400, [], json_encode(['error' => 'Bad request']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		$options = null;
		foreach ([
			$client->applications->getApplications(ApplicationService::FILTER_ALL, $options),
			$client->applications->getApplications(ApplicationService::FILTER_ONE_CLICK),
			$client->applications->getApplications(ApplicationService::FILTER_MARKETPLACE)
		] as $apps)
		{
			foreach ($apps as $app)
			{
				$this->assertInstanceOf(Application::class, $app);
				foreach ($data['applications'] as $object)
				{
					if ($object['id'] !== $app->getId()) continue;
					foreach ($app->toArray() as $attr => $value)
					{
						$this->assertEquals($value, $object[$attr]);
					}
				}
			}
		}

		$this->assertInstanceOf(ListOptions::class, $options);
		$this->assertEquals($options->getPerPage(), 150);
		$this->assertEquals($options->getTotal(), $data['meta']['total']);
		$this->assertEquals($options->getNextCursor(), 'next');
		$this->assertEquals($options->getPrevCursor(), 'prev');

		$this->expectException(ApplicationException::class);
		$client->applications->getApplications();
	}

	public function testGetApplicationsFilterOneClick()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testGetApplicationsFilterMarketplace()
	{
		$this->markTestIncomplete('Not implemented');
	}
}
