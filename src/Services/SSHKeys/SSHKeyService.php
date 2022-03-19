<?php

namespace Vultr\VultrPhp\Services\SSHKeys;

use Vultr\VultrPhp\Services\VultrServiceException;
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
	public function getSSHKeys(?ListOptions $options = null) : array
	{
		return $this->getListObjects('ssh-keys', new SSHKey(), $options);
	}

	public function updateSSHKey(SSHKey $ssh_key) : void
	{
		$this->patchObject('ssh-keys/'.$ssh_key->getId(), $ssh_key);
	}

	public function deleteSSHKey(string $ssh_key_id) : void
	{

	}

	public function createSSHKey(string $name, string $ssh_key) : SSHKey
	{

	}
}

