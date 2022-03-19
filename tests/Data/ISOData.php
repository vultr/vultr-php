<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class ISOData extends DataProvider
{
	public function dataGetISO(string $id) : array
	{
		return json_decode('
			{
				"iso": {
				"id": "'.$id.'",
				"date_created": "2020-10-10T01:56:20+00:00",
				"filename": "my-iso.iso",
				"size": 120586240,
				"md5sum": "77ba289bdc966ec996278a5a740d96d8",
				"sha512sum": "2b31b6fcab34d6ea9a6b293601c39b90cb044e5679fcc5",
				"status": "complete"
				}
			}
		', true);
	}
}
