<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services;

use Throwable;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\ModelInterface;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClient;
use Vultr\VultrPhp\VultrClientException;
use Vultr\VultrPhp\VultrClientHandler;
use Vultr\VultrPhp\VultrException;

abstract class VultrService
{
	protected VultrClient $vultr;
	private VultrClientHandler $client;

	public function __construct(VultrClient $vultr, VultrClientHandler $client)
	{
		$this->vultr = $vultr;
		$this->client = $client;
	}

	protected function getVultrClient() : VultrClient
	{
		return $this->vultr;
	}

	protected function getClientHandler() : VultrClientHandler
	{
		return $this->client;
	}

	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object that will be mapped to the get response.
	 * @throws Child of VultrServiceObject
	 * @return ModelInterface
	 */
	protected function getObject(string $uri, ModelInterface $model) : ModelInterface
	{
		try
		{
			$response = $this->getClientHandler()->get($uri);
		}
		catch (VultrClientException $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to get '.$this->getReadableClassType($model). ' info: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject((string)$response->getBody(), clone $model, $model->getResponseName());
	}

	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object that will be mapped to the get response.
	 * @param $options - ListOptions - Pagination object
	 * @param $params - array - filter parameters.
	 * @throws Child of VultrServiceObject
	 * @return ModelInterface[]
	 */
	protected function getListObjects(string $uri, ModelInterface $model, ?ListOptions &$options = null, ?array $params = null) : array
	{
		if ($options === null)
		{
			$options = new ListOptions(100);
		}

		$objects = [];
		try
		{
			$objects = $this->list($uri, clone $model, $options, $params);
		}
		catch (VultrServiceException $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to list '.$model->getResponseListName().': '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $objects;
	}

	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object that will be mapped to the get response.
	 * @throws Child of VultrServiceObject
	 * @return ModelInterface[]
	 */
	protected function getNonPaginatedListObjects(string $uri, ModelInterface $model) : array
	{
		try
		{
			$response = $this->getClientHandler()->get($uri);
			$stdclass = VultrUtil::decodeJSON((string)$response->getBody());

			// Get the list name from the model
			$list_name = $model->getResponseListName();

			// Ensure the expected list exists
			if (!isset($stdclass->$list_name)) {
				throw new VultrServiceException("Response does not contain expected list: $list_name");
			}

			// Map each item in the list to a model instance
			$objects = [];
			foreach ($stdclass->$list_name as $object) {
				$objects[] = VultrUtil::mapObject($object, clone $model);
			}

			return $objects;
		}
		catch (Throwable $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to list '.$model->getResponseListName().': '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}


	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object model that we are updating. This needs to be a fully initialized object.
	 * @throws Child of VultrServiceObject
	 * @return void
	 */
	protected function patchObject(string $uri, ModelInterface $model, ?array $params = null) : void
	{
		$payload = $model->getUpdateArray();
		if ($params !== null)
		{
			$payload = $params;
		}

		try
		{
			$this->getClientHandler()->patch($uri, $payload);
		}
		catch (VultrClientException $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to update '.$this->getReadableClassType($model).': '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object model that we are acting on deleting. This doesn't need to be a fully initialized object.
	 * @throws Child of VultrServiceObject
	 * @return void
	 */
	protected function deleteObject(string $uri, ModelInterface $model) : void
	{
		try
		{
			$this->getClientHandler()->delete($uri);
		}
		catch (VultrClientException $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to delete '.$this->getReadableClassType($model).': '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}

	/**
	 * @param $uri - string - the url address to query after api.vultr.com/v2
	 * @param $model - ModelInterface - the object model that we are creating
	 * @param $params - array - The values that we will be sending. Refactor to use getUpdateParams/getUpdateArray?
	 * @throws Child of VultrServiceObject
	 * @return ModelInterface
	 */
	protected function createObject(string $uri, ModelInterface $model, array $params) : ModelInterface
	{
		try
		{
			$response = $this->getClientHandler()->post($uri, $params);
		}
		catch (VultrClientException $e)
		{
			$exception_class = $model->getModelExceptionClass();
			throw new $exception_class('Failed to create '.$this->getReadableClassType($model).': '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject((string)$response->getBody(), clone $model, $model->getResponseName());
	}

	protected function list(string $uri, ModelInterface $model, ListOptions &$options, ?array $params = null) : array
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

			$response = $this->getClientHandler()->get($uri, $params);
		}
		catch (VultrClientException $e)
		{
			throw new VultrServiceException('Failed to list: '.$e->getMessage(), VultrException::SERVICE_CODE, $e->getHTTPCode(), $e);
		}

		$objects = [];
		try
		{
			$stdclass = VultrUtil::decodeJSON((string)$response->getBody());
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

	private function getReadableClassType(ModelInterface $model) : string
	{
		return str_replace('_', ' ', $model->getResponseName());
	}
}
