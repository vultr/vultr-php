<?php

namespace Vultr\VultrPhp;

use Throwable;
use InvalidArgumentException;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

class VultrClientHandler
{
	private ClientInterface $client;
	private RequestFactoryInterface $request_fact;
	private ResponseFactoryInterface $response_fact;
	private StreamFactoryInterface $stream_fact;

	private VultrAuth $auth;

	private const QUERY = 0;
	private const JSON = 1;

	public function __construct(
		VultrAuth $auth,
		ClientInterface $http,
		RequestFactoryInterface $request,
		ResponseFactoryInterface $response,
		StreamFactoryInterface $stream
	)
	{
		$this->auth = $auth;

		$this->setClient($http);
		$this->setRequestFactory($request);
		$this->setResponseFactory($response);
		$this->setStreamFactory($stream);
	}

	public function setClient(ClientInterface $http) : void
	{
		$this->client = $http;
	}

	public function setRequestFactory(RequestFactoryInterface $request) : void
	{
		$this->request_fact = $request;
	}

	public function setResponseFactory(ResponseFactoryInterface $response) : void
	{
		$this->response_fact = $response;
	}

	public function setStreamFactory(StreamFactoryInterface $stream) : void
	{
		$this->stream_fact = $stream;
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrClientException
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
	 * @throws VultrClientException
	 * @return ResponseInterface
	 */
	public function post(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('POST', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrClientException
	 * @return ResponseInterface
	 */
	public function put(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('PUT', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array - form data that will be encoded to a json
	 * @throws VultrClientException
	 * @return ResponseInterface
	 */
	public function patch(string $uri, array $params = []) : ResponseInterface
	{
		return $this->request('PATCH', $uri, [self::JSON => $params]);
	}

	/**
	 * @param $uri - string - anything after api.vultr.com/v2/
	 * @param $params - array|null - query parameters that will be added to the uri query stirng.
	 * @throws VultrClientException
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
			$request = $this->request_fact->createRequest($method, VultrConfig::getBaseURI().ltrim($uri, '/'));
			foreach (VultrConfig::generateHeaders($this->auth) as $header => $value)
			{
				$request = $request->withHeader($header, $value);
			}

			$request = $this->applyOptions($request, $options);
			$response = $this->client->sendRequest($request);
		}
		catch (ClientExceptionInterface $e)
		{
			throw new VultrClientException($this->formalizeErrorMessage($this->response_fact->createResponse(500), $e->getRequest()), null, $e);
		}
		catch (Throwable $e)
		{
			throw new VultrClientException($method.' fatal failed: '.$e->getMessage(), null, $e);
		}

		$level = $this->getLevel($response);
		if ($level >= 4)
		{
			$message = $this->formalizeErrorMessage($response, $request);
			throw new VultrClientException($method.' failed: '.$message, $response->getStatusCode());
		}

		return $response;
	}

	private function applyOptions(RequestInterface $request, array &$options) : RequestInterface
	{
		if (isset($options[self::JSON]))
		{
			$json = json_encode($options[self::JSON], 0, 512);
			if (JSON_ERROR_NONE !== json_last_error())
			{
				throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
			}
			$request = $request->withBody($this->stream_fact->createStream($json));
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

			$request = $request->withUri($request->getUri()->withQuery($value), true);
			unset($options[self::QUERY]);
		}

		return $request;
	}

	private function formalizeErrorMessage(ResponseInterface $response, RequestInterface $request) : string
	{
		$error = json_decode($response->getBody(), true);
		if (isset($error['error']))
		{
			return $error['error'];
		}

		$level = $this->getLevel($response);

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

		if ($summary = $this->bodySummary($response) !== null)
		{
			$message .= "\n{$summary}\n";
		}

		return $message;
	}

	private function bodySummary(MessageInterface $message, int $truncateAt = 120): ?string
	{
		$body = $message->getBody();

		if (!$body->isSeekable() || !$body->isReadable())
		{
			return null;
		}

		$size = $body->getSize();

		if ($size === 0)
		{
			return null;
		}

		$summary = $body->read($truncateAt);
		$body->rewind();

		if ($size > $truncateAt)
		{
			$summary .= ' (truncated...)';
		}

		// Matches any printable character, including unicode characters:
		// letters, marks, numbers, punctuation, spacing, and separators.
		if (preg_match('/[^\pL\pM\pN\pP\pS\pZ\n\r\t]/u', $summary))
		{
			return null;
		}

		return $summary;
	}

	private function getLevel(ResponseInterface $response) : int
	{
		return (int) floor($response->getStatusCode() / 100);
	}
}
