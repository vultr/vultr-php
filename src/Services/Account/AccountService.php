<?php

namespace Vultr\VultrPhp\Services\Account;

use Throwable;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;

class AccountService extends VultrService
{
	/**
	 * Get the Account Model with information for the logged in API Key.
	 * @throws
	 */
	public function getAccount() : Account
	{
		try
		{
			$response = $this->get('account');
		}
		catch (VultrServiceException $e)
		{
			throw new AccountException('Failed to get account info: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new Account(), 'account');
	}
}
