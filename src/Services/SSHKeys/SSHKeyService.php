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

	public function getSSHKeys(?ListOptions $options = null) : array
	{

	}

	public function updateSSHKey(SSHKey $ssh_key) : void
	{

	}

	public function deleteSSHKey(string $ssh_key_id) : void
	{

	}

	public function createSSHKey(string $name, string $ssh_key) : SSHKey
	{

	}
}

