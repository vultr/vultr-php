<?php

namespace Vultr\VultrPhp\Services\Account;

use Exception;
use Error;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;

class AccountService extends VultrService
{
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

		try
		{
			$stdclass = json_decode($response->getBody());
			$account = VultrUtil::mapObject($stdclass, new Account(), 'account');
		}
		catch (Exception | Error $e)
		{
			throw new AccountException('Failed to deserialize account object: '.$e->getMessage(), null, $e);
		}

		return $account;
	}
}
