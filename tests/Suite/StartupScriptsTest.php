<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\StartupScripts\StartupScriptException;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Vultr\VultrPhp\Tests\VultrTest;

class StartupScriptsTest extends VultrTest
{
	public function testGetStartupScript()
	{
		$startup_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data = $this->getDataProvider()->getData($startup_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new RequestException('Bad Request', new Request('GET', 'startup-scripts/'.$startup_id), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$script = $client->startup_scripts->getStartupScript($startup_id);
		foreach ($script->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['startup_script'][$attr]);
		}

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->getStartupScript($startup_id);
	}

	public function testGetStartupScripts()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testCreateStartupScript()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testUpdateStartupScript()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteStartupScript()
	{
		$this->markTestSkipped('Incomplete');
	}
}
