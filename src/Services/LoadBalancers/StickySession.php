<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

class StickySession extends Model
{
	protected string $cookieName;

	public function getCookieName() : string
	{
		return $this->cookieName;
	}

	public function setCookieName(string $cookie_name) : void
	{
		$this->cookieName = $cookie_name;
	}
}
