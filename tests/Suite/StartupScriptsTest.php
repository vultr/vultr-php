<?php

namespace Vultr\VultrPhp\Tests\Suite;

use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Services\StartupScripts\StartupScript;
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
		$startup_script = new StartupScript();
		$startup_script->setName('My name of startupscript');
		$startup_script->setType('boot');
		$startup_script->setScript(base64_encode('echo "hello world"'));

		$data = $startup_script->toArray();
		$data['id'] = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data['date_created'] = '2020-10-10T01:56:20+00:00';
		$data['date_modified'] = '2020-10-10T01:56:20+00:00';

		$client = $this->getDataProvider()->createClientHandler([
			new Response(201, ['Content-Type' => 'application/json'], json_encode(['startup_script' => $data])),
			new RequestException('Bad Request', new Request('POST', 'startup-scripts'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$script = $client->startup_scripts->createStartupScript($startup_script);
		$this->assertInstanceOf(StartupScript::class, $script);
		$array = $script->toArray();
		foreach ($script->toArray() as $prop => $prop_val)
		{
			$this->assertEquals($prop_val, $data[$prop]);
		}

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->createStartupScript($startup_script);
	}

	public function testUpdateStartupScript()
	{
		$this->markTestSkipped('Incomplete');
	}

	public function testDeleteStartupScript()
	{
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new RequestException('Bad Request', new Request('DEL', 'iso'), new Response(400, [], json_encode(['error' => 'Bad Request']))),
		]);

		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client->startup_scripts->deleteStartupScript($id);

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->deleteStartupScript($id);
	}
}
