<?php

namespace Vultr\VultrPhp;

use Throwable;
use InvalidArgumentException;

use Vultr\VultrPhp\Services\VultrServiceException;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\UriResolver;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Message;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class VultrClientHandler
{
	private ClientInterface $client;
	private VultrAuth $auth;

	private const QUERY = 0;
	private const JSON = 1;

	public function __construct(ClientInterface $http, VultrAuth $auth)
	{
		$this->client = $http;
		$this->auth = $auth;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	public function delete(string $uri, ?array $params = []) : ResponseInterface
	{
		$options = [];
		if ($params !== null)
		{
			$options[self::QUERY] = $params;
		}

		return $this->request('DELETE', $uri, $options);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	public function post(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('POST', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	public function put(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('PUT', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	public function patch(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('PATCH', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrServiceException
	 * @return ResponseInterface
	 */
	public function get(string $uri, ?array $params = null) : ResponseInterface
	{
		$options = [];
		if ($params !== null)
		{
			$options[self::QUERY] = $params;
		}

		return $this->request('GET', $uri, $options);
	}

	private function request(string $method, string $uri, array $options = []) : ResponseInterface
	{
		try
		{
			$merged_uri = UriResolver::resolve(Utils::uriFor(VultrConfig::getBaseURI()), Utils::uriFor($uri));

			$request = new Request($method, $merged_uri, VultrConfig::generateHeaders($this->auth));
			$request = $this->applyOptions($request, $options);
			$response = $this->client->sendRequest($request);
		}
		catch (NetworkExceptionInterface|RequestExceptionInterface $e)
		{
			throw new VultrClientException($this->formalizeErrorMessage(new Response(500), $e->getRequest()), null, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrClientException($method.' fatal failed: '.$e->getMessage(), null, $e);
		}


		$level = (int) floor($response->getStatusCode() / 100);
		if ($level >= 4)
		{
			$message = $this->formalizeErrorMessage($response, $request);
			throw new VultrClientException($method.' failed: '.$message, $response->getStatusCode());
		}

		return $response;
	}

	private function applyOptions(RequestInterface $request, array &$options) : RequestInterface
	{
		$modify = [];
		if (isset($options[self::JSON]))
		{
			$json = json_encode($options[self::JSON], 0, 512);
			if (JSON_ERROR_NONE !== json_last_error())
			{
				throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
			}
			$options['body'] = $json;
			unset($options[self::JSON]);
		}

		if (isset($options[self::QUERY]))
		{
			$value = $options[self::QUERY];
			if (is_array($value))
			{
				$value = http_build_query($value, '', '&', PHP_QUERY_RFC3986);
			}

			if (!is_string($value))
			{
				throw new InvalidArgumentException('query must be a string or array');
			}
			$modify['query'] = $value;
			unset($options[self::QUERY]);
		}

		return Utils::modifyRequest($request, $modify);
	}

	private function formalizeErrorMessage(ResponseInterface $response, RequestInterface $request) : string
	{
		$error = json_decode($response->getBody(), true);
		if (isset($error['error']))
		{
			return $error['error'];
		}

		$label = 'Unsuccessful request';
		if ($level === 4) $label = 'Client error';
		else if ($level === 5) $label = 'Server error';

		$message = sprintf(
			'%s: `%s %s` resulted in a `%s %s` response.',
			$label,
			$request->getMethod(),
			$request->getUri(),
			$response->getStatusCode(),
			$response->getReasonPhrase()
		);

		$message .= "\n".Message::bodySummary($response)."\n";

		return $message;
	}
}
