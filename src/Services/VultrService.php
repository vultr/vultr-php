<?php

namespace Vultr\VultrPhp\Services;

// Dependancies.
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

use Vultr\VultrPhp\VultrException;
use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Util\ListOptions;

abstract class VultrService
{
	protected VultrClient $vultr;
	private Client $client;

	public function __construct(VultrClient $vultr, Client $client)
	{
		$this->vultr = $vultr;
		$this->client = $client;
	}

	protected function getVultrClient() : VultrClient
	{
		return $this->vultr;
	}

	protected function getClient() : Client
	{
		return $this->client;
	}

	protected function get(string $uri, ?array $params = null) : ResponseInterface
	{
		$options = [];
		if ($params !== null)
		{
			$options[RequestOptions::QUERY] = $params;
		}

		try
		{
			$response = $this->getClient()->request('GET', $uri, $options);
		}
		catch (RequestException $e)
		{
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$message = $e->getMessage();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}

				throw new VultrServiceException('GET failed : '.$message, VultrException::SERVICE_CODE, $response->getStatusCode(), $e);
			}
			throw new VultrServiceException('GET failed : '. $e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}
		catch (Exception $e)
		{
			throw new VultrServiceException('GET failed : '.$e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}

		return $response;
	}

	protected function list(string $uri, ModelInterface $model, ListOptions $options, ?array $params = null) : array
	{
		try
		{
			if ($params === null)
			{
				$params = [];
			}
			$params['per_page'] = $options->getPerPage();

			if ($options->getCurrentCursor() != '')
			{
				$params['cursor'] = $options->getCurrentCursor();
			}

			$response = $this->get($uri, $params);
		}
		catch (VultrServiceException $e)
		{
			throw new VultrServiceException('Failed to list: '.$e->getMessage(), $e->getHTTPCode());
		}

		$objects = [];
		try
		{
			$stdclass = json_decode($response->getBody());
			$options->setTotal($stdclass->meta->total);
			$options->setNextCursor($stdclass->meta->links->next);
			$options->setPrevCursor($stdclass->meta->links->prev);
			$list_name = $model->getResponseListName();
			foreach ($stdclass->$list_name as $object)
			{
				$objects[] = VultrUtil::mapObject($object, $model);
			}
		}
		catch (Exception $e)
		{
			throw new VultrServiceException('Failed to deserialize list: '. $e->getMessage());
		}

		return $objects;
	}
}
