<?php

namespace Vultr\VultrPhp\Services;

use Throwable;
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

	/**
	 * Get the Guzzle Client
	 */
	protected function getClient() : Client
	{
		return $this->client;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	protected function delete(string $uri, ?array $params = []) : ResponseInterface
	{
		$options = [];
		if ($params !== null)
		{
			$options[RequestOptions::QUERY] = $params;
		}

		try
		{
			$response = $this->getClient()->request('DELETE', $uri, $options);
		}
		catch (RequestException $e)
		{
			$code = null;
			$message = $e->getMessage();
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$code = $response->getStatusCode();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}
			}
			throw new VultrServiceException('DELETE failed : '.$message, VultrException::SERVICE_CODE, $code, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrServiceException('DELETE fatal failed : '.$e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}

		return $response;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	protected function post(string $uri, array $params = []) : ResponseInterface
	{
		try
		{
			$response = $this->getClient()->request('POST', $uri, [
				RequestOptions::JSON => $params
			]);
		}
		catch (RequestException $e)
		{
			$code = null;
			$message = $e->getMessage();
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$code = $response->getStatusCode();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}
			}
			throw new VultrServiceException('POST failed : '.$message, VultrException::SERVICE_CODE, $code, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrServiceException('POST fatal failed : '.$e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}

		return $response;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	protected function put(string $uri, array $params = []) : ResponseInterface
	{
		try
		{
			$response = $this->getClient()->request('PUT', $uri, [
				RequestOptions::JSON => $params
			]);
		}
		catch (RequestException $e)
		{
			$code = null;
			$message = $e->getMessage();
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$code = $response->getStatusCode();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}
			}
			throw new VultrServiceException('PUT failed : '.$message, VultrException::SERVICE_CODE, $code, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrServiceException('PUT fatal failed : '.$e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}

		return $response;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
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
			$code = null;
			$message = $e->getMessage();
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$error = json_decode($response->getBody(), true);
				$code = $response->getStatusCode();
				if (isset($error['error']))
				{
					$message = $error['error'];
				}
			}
			throw new VultrServiceException('GET failed : '.$message, VultrException::SERVICE_CODE, $code, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrServiceException('GET fatal failed : '.$e->getMessage(), VultrException::SERVICE_CODE, null, $e);
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
			throw new VultrServiceException('Failed to list: '.$e->getMessage(), VultrException::SERVICE_CODE, $e->getHTTPCode(), $e);
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
		catch (Throwable $e)
		{
			throw new VultrServiceException('Failed to deserialize list: '. $e->getMessage(), VultrException::SERVICE_CODE, null, $e);
		}

		return $objects;
	}
}
