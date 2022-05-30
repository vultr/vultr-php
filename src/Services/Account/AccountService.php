<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Account;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;

class AccountService extends VultrService
{
	/**
	 * Get the Account Model with information for the logged in API Key.
	 * @throws AccountException
	 * @throws VultrException
	 * @return Account
	 */
	public function getAccount() : Account
	{
		return $this->getObject('account', new Account());
	}
}
