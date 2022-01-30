<?php

namespace Vultr\VultrPhp;

class VultrClient
{
	private VultrAuth $auth;

	public function __construct(VultrAuth $auth)
	{
		$this->auth = $auth;
	}
}
