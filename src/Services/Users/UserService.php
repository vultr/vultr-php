<?php

namespace Vultr\VultrPhp\Services\Users;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;

class UserService extends VultrService
{
	/**
	 * @param $user_id - string - UUID of the user
	 * @throws UserException
	 * @throws VultrException
	 * @return User
	 */
	public function getUser(string $user_id) : User
	{
		try
		{
			$response = $this->get('users/'.$user_id);
		}
		catch (VultrServiceException $e)
		{
			throw new UserException('Failed to get user info: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new User(), 'user');
	}

	public function getUsers(?ListOptions &$options = null) : array
	{

	}

	public function createUser(User $user) : User
	{

	}

	public function updateUser(User $user) : void
	{

	}

	public function deleteUser(string $user_id) : void
	{

	}
}

