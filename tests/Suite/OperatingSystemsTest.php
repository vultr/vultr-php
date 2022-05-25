<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\OperatingSystems\OperatingSystem;
use Vultr\VultrPhp\Services\OperatingSystems\OperatingSystemException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

use Vultr\VultrPhp\Tests\VultrTest;

class OperatingSystemsTest extends VultrTest
{
	public function testGetOperatingSystems()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad request'])),
		]);

		foreach ($client->operating_system->getOperatingSystems() as $os)
		{
			$this->assertInstanceOf(OperatingSystem::class, $os);
			foreach ($data[$os->getResponseListName()] as $object)
			{
				if ($object['id'] !== $os->getId()) continue;
				foreach ($os->toArray() as $attr => $value)
				{
					$this->assertEquals($value, $object[$attr]);
				}
				break;
			}
		}

		$this->expectException(OperatingSystemException::class);
		$client->operating_system->getOperatingSystems();
	}

	public function testGetOperatingSystem()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
		]);
		$id = 124;
		$selected_os = null;
		$os = $client->operating_system->getOperatingSystem($id);
		foreach ($data[$os->getResponseListName()] as $os_response)
		{
			if ($os_response['id'] !== $id) continue;

			$selected_os = $os_response;
			break;
		}
		$this->assertNotNull($selected_os, 'Failed to find an operating system, id needs to be adjusted.');

		$this->assertInstanceOf(OperatingSystem::class, $os);
		foreach ($os->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $selected_os[$attr]);
		}
	}
}
