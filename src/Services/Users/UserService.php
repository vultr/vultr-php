<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Users;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

/**
 * User service handler, for all users endpoints.
 *
 * @see https://www.vultr.com/api/#tag/users
 */
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

		return $this->createObject('users', new User(), $params);
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
		$this->deleteObject('users/'.$user_id, new User());
	}
}

