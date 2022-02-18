<?php

namespace Vultr\VultrPhp\Services\Kubernetes;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;

class KubernetesException extends VultrServiceException
{
	public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
	{
		parent::__construct($message, VultrServiceException::KUBERNETES_CODE, $http_code, $previous);
	}
}
