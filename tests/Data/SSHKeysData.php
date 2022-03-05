<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class SSHKeysData extends DataProvider
{
	protected function dataGetSSHKey(string $ssh_id) : array
	{
		return json_decode('{
  "ssh_key": {
    "id": "'.$ssh_id.'",
    "date_created": "2020-10-10T01:56:20+00:00",
    "name": "Example SSH Key",
    "ssh_key": "ssh-rsa AA... user@example.com"
  }
}', true);
	}
}
