<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class StartupScriptsData extends DataProvider
{
	protected function dataGetStartupScript(string $startup_id) : array
	{
		return json_decode('{
  "startup_script": {
	"id": "'.$startup_id.'",
	"date_created": "2020-10-10T01:57:20+00:00",
	"date_modified": "2020-10-10T01:59:20+00:00",
	"name": "Example Startup Script",
	"type": "pxe",
	"script": "QmFzZTY0IEV4YW1wbGUgRGF0YQ=="
  }
}', true);
	}
}
