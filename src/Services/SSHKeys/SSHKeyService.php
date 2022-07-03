<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\SSHKeys;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class SSHKeyService extends VultrService
{
	/**
	 * @param $ssh_key_id - string - UUID of the ssh key
	 * @throws SSHKeyException
	 * @throws VultrException
	 * @return SSHKey
	 */
	public function getSSHKey(string $ssh_key_id) : SSHKey
	{
		return $this->getObject('ssh-keys/'.$ssh_key_id, new SSHKey());
	}

	/**
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws SSHKeyException
	 * @return SSHKey[]
	 */
	public function getSSHKeys(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('ssh-keys', new SSHKey(), $options);
	}

	/**
	 * @param $ssh_key - SSHKey
	 * @throws SSHKeyException
	 * @return void
	 */
	public function updateSSHKey(SSHKey $ssh_key) : void
	{
		$this->patchObject('ssh-keys/'.$ssh_key->getId(), $ssh_key);
	}

	/**
	 * @param $ssh_key_id - string - UUID of the ssh key
	 * @throws SSHKeyException
	 * @return void
	 */
	public function deleteSSHKey(string $ssh_key_id) : void
	{
		$this->deleteObject('ssh-keys/'.$ssh_key_id, new SSHKey());
	}

	/**
	 * @param $name - string - what are you going to call the key.
	 * @param $ssh_key - string - full ssh key
	 * @throws SSHKeyException
	 * @return void
	 */
	public function createSSHKey(string $name, string $ssh_key) : SSHKey
	{
		return $this->createObject('ssh-keys', new SSHKey(), [
			'name'    => $name,
			'ssh_key' => $ssh_key
		]);
	}
}

