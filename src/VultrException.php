<?php

namespace Vultr\VultrPhp;

use Exception;

class VultrException extends Exception
{
	public const DEFAULT_CODE = 300;

	public function __construct(string $message, int $code = self::DEFAULT_CODE)
	{
		parent::__construct($message, $code);
	}
}
