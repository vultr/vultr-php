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
		return $this->getObject('users/'.$user_id, new User());
	}

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws UserException
	 * @return User[]
	 */
	public function getUsers(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('users', new User(), $options);
	}

	/**
	 * @param $password - string
	 * @param $user - User Model with any properties defined will be used in the response.
	 * @throws UserException
	 * @throws VultrException
	 * @return User
	 */
	public function createUser(string $password, User $user) : User
	{
		$params = $user->toArray();
		$params['password'] = $password;
		foreach ($params as $attr => $param)
		{
			if (empty($param)) unset($params[$attr]);
		}

		try
		{
			$response = $this->post('users', $params);
		}
		catch (VultrServiceException $e)
		{
			throw new UserException('Failed to create user: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new User(), 'user');
	}

	/**
	 * @param $user - User
	 * @throws UserException
	 * @return void
	 */
	public function updateUser(User $user) : void
	{
		$this->patchObject('users/'.$user->getId(), $user);
	}

	/**
	 * @param $user_id - string
	 * @throws UserException
	 * @return void
	 */
	public function deleteUser(string $user_id) : void
	{
		try
		{
			$this->delete('users/'.$user_id);
		}
		catch (VultrServiceException $e)
		{
			throw new UserException('Failed to delete user: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}

