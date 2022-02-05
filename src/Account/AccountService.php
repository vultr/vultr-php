<?php

namespace Vultr\VultrPhp\Account;

use Vultr\VultrPhp\VultrException;
use Vultr\VultrPhp\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;

class AccountService extends VultrService
{
	public function getAccount() : Account
	{
		$client = $this->getClient();

		try
		{
			$response = $client->get('account');
		}
		catch (VultrException $e)
		{
			throw new AccountException('Failed to get account info: '.$e->getMessage(), $e->getHTTPCode());
		}

		try
		{
			$stdclass = json_decode($response->getBody());
			$account = VultrUtil::mapObject($stdclass, new Account(), 'account');
		}
		catch (Exception $e)
		{
			throw new AccountException('Failed to deserialize account object: '.$e->getMessage());
		}

		return $account;
	}
}
