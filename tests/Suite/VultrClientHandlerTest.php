<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use ReflectionClass;
use Vultr\VultrPhp\Tests\VultrTest;
use Vultr\VultrPhp\VultrAuth;
use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\VultrClientHandler;

class VultrClientHandlerTest extends VultrTest
{
	public function testCRUD()
	{
		foreach (['post', 'patch', 'get', 'delete', 'put'] as $action)
		{
			$this->testCallback(function ($client) use ($action) {
				return $client->$action('test1234');
			});

			$this->testCallback(function ($client) use ($action) {
				return $client->$action('test1234', ['same_i_iam' => 'green eggs and ham']);
			});

			$this->testErrorReturn(function ($client) use ($action) {
				$client->$action('test1234');
			});

			$this->testErrorReturn(function ($client) use ($action) {
				$client->$action('test1234', ['same_i_iam' => 'green eggs and ham']);
			});
		}
	}

	private function generateClient(array $responses) : VultrClientHandler
	{
		$guzzle_factory = new HttpFactory();
		$mock = new MockHandler($responses);
		$guzzle_client = new Client(['handler' => HandlerStack::create($mock)]);
		return new VultrClientHandler(new VultrAuth('Test1234'), $guzzle_client, $guzzle_factory, $guzzle_factory, $guzzle_factory);
	}

	private function generateGenericResponses() : array
	{
		$responses = [];
		foreach (array_keys($this->getHTTPCodesFromGuzzle()) as $http_code)
		{
			$responses[] = new Response($http_code);
		}

		return $responses;
	}

	private function getHTTPCodesFromGuzzle() : array
	{
		$reflect = new ReflectionClass(Response::class);

		return $reflect->getConstants()['PHRASES'];
	}

	private function testCallback(Closure $function) : void
	{
		$responses = $this->generateGenericResponses();
		$client = $this->generateClient($responses);

		foreach ($responses as $response)
		{
			try
			{
				$tmp_response = $function($client);
			}
			catch (VultrClientException $e)
			{
				$this->assertEquals($response->getStatusCode(), $e->getHTTPCode());
				$this->assertGreaterThanOrEqual(400, $e->getHTTPCode());
			}

			$this->assertLessThan(400, $tmp_response->getStatusCode());
		}
	}

	private function testErrorReturn(Closure $function) : void
	{
		$error = 'I am a wonderful error';
		$client = $this->generateClient([
			new Response(400, ['Content-Type: application/json'], json_encode([
				'error' => $error
			])),
		]);

		try
		{
			$function($client);
		}
		catch (VultrClientException $e)
		{
			$this->assertStringContainsString($error, $e->getMessage());
		}
	}
}
