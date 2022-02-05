<?php

namespace Vultr\VultrPhp;

use Exception;
use Throwable;

class VultrException extends Exception
{
	public const DEFAULT_CODE = 300;
	public const ACCOUNT_CODE = 301;
	public const APPLICATION_CODE = 302;
	public const BACKUP_CODE = 303;
	public const BLOCK_CODE = 304;
	public const DNS_CODE = 305;
	public const FIREWALL_CODE = 306;
	public const INSTANCE_CODE = 307;
	public const ISO_CODE = 308;
	public const KUBERNETES_CODE = 309;
	public const LB_CODE = 310; // Load balancers
	public const OBJ_CODE = 311; // Object Storage
	public const OS_CODE = 312; // Operating System
	public const PLAN_CODE = 313;
	public const VPC_CODE = 314; // Private Networks
	public const RESERVED_IP_CODE = 315;
	public const REGION_CODE = 316;
	public const SNAPSHOT_CODE = 317;
	public const SSH_CODE = 318;
	public const STARTUP_CODE = 319;
	public const USER_CODE = 320;
	public const CLIENT_CODE = 400;

	protected ?int $http_code = null;

	public function __construct(string $message, int $code = self::DEFAULT_CODE, ?Throwable $previous = null, ?int $http_code = null)
	{
		$this->http_code = $http_code;
		parent::__construct($message, $code, $previous);
	}

	public function getHTTPCode() : ?int
	{
		return $this->http_code;
	}
}
