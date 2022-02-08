<?php

namespace Vultr\VultrPhp\Tests;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\Applications\Application;
use Vultr\VultrPhp\Services\Applications\ApplicationService;
use Vultr\VultrPhp\Services\Applications\ApplicationException;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class ApplicationsTest extends TestCase
{
	public function testGetApplications()
	{
		$data = [
			'applications' => [
				[
					'id'          =>  1,
					'name'        =>  'LEMP',
					'short_name'  =>  'lemp',
					'deploy_name' =>  'LEMP on CentOS 6 x64',
					'type'        =>  'one-click',
					'vendor'      =>  'vultr',
					'image_id'    =>  ''
				],
				[
					'id'          => 1028,
					'name'        => 'OpenLiteSpeed WordPress',
					'short_name'  => 'openlitespeedwordpress',
					'deploy_name' => 'OpenLiteSpeed WordPress on Ubuntu 20.04 x64',
					'type'        => 'marketplace',
					'vendor'      => 'LiteSpeed_Technologies',
					'image_id'    => 'openlitespeed-wordpress'
				],
			],
			'meta' => [
				'total' => 2,
				'links' => [
					'next' => '',
					'prev' => '',
				]
			]
		];

		// Filter one-click
		$data2 = $data;
		unset($data2['applications'][1]);
		$dat2['meta']['total'] = 1;

		// Filter marketplace
		$data3 = $data;
		unset($data3['applications'][0]);
		$dat3['meta']['total'] = 1;

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data2)),
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data3)),
			new RequestException('This is an exception', new Request('GET', 'applications'), new Response(400, [], json_encode(['error' => 'Bad request']))),
		]);
		$stack = HandlerStack::create($mock);
		$client = VultrClient::create('TEST1234', ['handler' => $stack]);

		foreach ([
			$client->applications->getApplications(),
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

		$this->expectException(ApplicationException::class);
		$client->applications->getApplications();
	}
}
