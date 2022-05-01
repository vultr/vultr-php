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

	protected function dataGetStartupScripts() : array
	{
		return json_decode('{
  "startup_scripts": [
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
      "date_created": "2020-10-10T01:56:20+00:00",
      "date_modified": "2020-10-10T01:59:20+00:00",
      "name": "Example Startup Script",
      "type": "pxe"
    },
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e252560",
      "date_created": "2020-10-10T01:56:20+00:00",
      "date_modified": "2020-10-10T01:59:20+00:00",
      "name": "Example Startup aaScript",
      "type": "boot"
    },
    {
      "id": "cb676a46-66fd-4dfb-b839-443f2e6ad231",
      "date_created": "2020-10-10T01:56:20+00:00",
      "date_modified": "2020-10-10T01:59:20+00:00",
      "name": "Example Startupss Script",
      "type": "pxe"
    }
  ],
  "meta": {
    "total": 3,
    "links": {
      "next": "",
      "prev": ""
    }
  }
}', true);
	}
}
