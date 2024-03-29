<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Account;

use Vultr\VultrPhp\Services\VultrService;

/**
 * Account service handler, for account endpoints.
 *
 * @see https://www.vultr.com/api/#tag/account
 */
class AccountService extends VultrService
{
	/**
	 * Get the Account Model with information for the logged in API Key.
	 *
	 * @throws AccountException
	 * @throws VultrException
	 * @return Account
	 */
	public function getAccount() : Account
	{
		return $this->getObject('account', new Account());
	}
}
