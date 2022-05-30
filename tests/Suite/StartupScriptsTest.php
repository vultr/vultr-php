<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\StartupScripts\StartupScript;
use Vultr\VultrPhp\Services\StartupScripts\StartupScriptException;
use Vultr\VultrPhp\Tests\VultrTest;

class StartupScriptsTest extends VultrTest
{
	public function testGetStartupScript()
	{
		$startup_id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$data = $this->getDataProvider()->getData($startup_id);

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$script = $client->startup_scripts->getStartupScript($startup_id);
		$this->assertInstanceOf(StartupScript::class, $script);
		foreach ($script->toArray() as $attr => $value)
		{
			$this->assertEquals($value, $data['startup_script'][$attr]);
		}

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->getStartupScript($startup_id);
	}

	public function testGetStartupScripts()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$scripts = $client->startup_scripts->getStartupScripts();
		$this->assertIsArray($scripts);
		$this->assertEquals($data['meta']['total'], count($scripts));
		foreach ($scripts as $script)
		{
			$this->assertInstanceOf(StartupScript::class, $script);
			foreach ($data[$script->getResponseListName()] as $object)
			{
				if ($object['id'] !== $script->getId()) continue;
				foreach ($script->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop]);
				}
				break;
			}
		}

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->getStartupScripts();
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
			new Response(201, ['Content-Type' => 'application/json'], json_encode([$startup_script->getResponseName() => $data])),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$script = $client->startup_scripts->createStartupScript($startup_script);
		$this->assertInstanceOf(StartupScript::class, $script);
		foreach ($script->toArray() as $prop => $prop_val)
		{
			$this->assertEquals($prop_val, $data[$prop]);
		}

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->createStartupScript($startup_script);
	}

	public function testUpdateStartupScript()
	{
		$script = new StartupScript();
		$script->setId('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
		$script->setName('My new name');
		$script->setScript(base64_encode('echo "new script"'));

		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->startup_scripts->updateStartupScript($script);

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->updateStartupScript($script);
	}

	public function testDeleteStartupScript()
	{
		$id = 'cb676a46-66fd-4dfb-b839-443f2e6c0b60';
		$client = $this->getDataProvider()->createClientHandler([
			new Response(204),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$client->startup_scripts->deleteStartupScript($id);

		$this->expectException(StartupScriptException::class);
		$client->startup_scripts->deleteStartupScript($id);
	}
}
