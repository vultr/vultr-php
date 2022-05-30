<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Services\Applications\Application;
use Vultr\VultrPhp\Services\Applications\ApplicationService;
use Vultr\VultrPhp\Services\Applications\ApplicationException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

use Vultr\VultrPhp\Tests\VultrTest;

class ApplicationsTest extends VultrTest
{
	public function testGetApplications()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		$options = null;
		$this->testApps($client->applications->getApplications(ApplicationService::FILTER_ALL, $options), $data);
		$this->testOptions($options, $data);

		$this->expectException(ApplicationException::class);
		$client->applications->getApplications();
	}

	public function testGetApplicationsFilterOneClick()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		$options = null;
		$this->testApps($client->applications->getApplications(ApplicationService::FILTER_ONE_CLICK, $options), $data);
		$this->testOptions($options, $data);

		$this->expectException(ApplicationException::class);
		$client->applications->getApplications(ApplicationService::FILTER_ONE_CLICK);
	}

	public function testGetApplicationsFilterMarketplace()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		$options = null;
		$this->testApps($client->applications->getApplications(ApplicationService::FILTER_MARKETPLACE, $options), $data);
		$this->testOptions($options, $data);

		$this->expectException(ApplicationException::class);
		$client->applications->getApplications(ApplicationService::FILTER_MARKETPLACE);
	}

	public function testGetApplication()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
		]);

		$app = $client->applications->getApplication(1);
		$this->assertInstanceOf(Application::class, $app);
		$selected = $data[$app->getResponseListName()][0]; // @see ApplicationsData::dataGetApplications
		foreach ($app->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $selected[$attr]);
		}

		$app = $client->applications->getApplication(69);
		$this->assertNull($app);
	}

	private function testApps(array $response, array $data)
	{
		foreach ($response as $app)
		{
			$this->assertInstanceOf(Application::class, $app);
			foreach ($data[$app->getResponseListName()] as $object)
			{
				if ($object['id'] !== $app->getId()) continue;
				foreach ($app->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
				break;
			}
		}
	}

	private function testOptions(ListOptions $options, array $data)
	{
		$this->assertInstanceOf(ListOptions::class, $options);
		$this->assertEquals($options->getPerPage(), 150);
		$this->assertEquals($options->getTotal(), $data['meta']['total']);
		$this->assertEquals($options->getNextCursor(), 'next');
		$this->assertEquals($options->getPrevCursor(), 'prev');
	}
}
