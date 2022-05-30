<?php

declare(strict_types=1);

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

	public function dataGetISOs() : array
	{
		return json_decode('
			{
				"isos": [
					{
						"id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
						"date_created": "2020-10-10T01:56:20+00:00",
						"filename": "my-iso.iso",
						"size": 120586240,
						"md5sum": "77ba289bdc966ec996278a5a740d96d8",
						"sha512sum": "2b31b6fcab34d6ea9a6b293601c39b90cb044e5679fcc5",
						"status": "complete"
					},
					{
						"id": "cb676a46-66fd-4dfb-b839-1415325362asd",
						"date_created": "2020-10-10T01:56:20+00:00",
						"filename": "my-iso.iso",
						"size": 1245151,
						"md5sum": "77ba289bdc966ec99627824241zfdsf",
						"sha512sum": "2b31b6fcab34d6ea9a6b293601c39b90cb4532125151",
						"status": "complete"
					}
				],
				"meta": {
					"total": 2,
					"links": {
						"next": "",
						"prev": ""
					}
				}
			}
		', true);
	}

	public function dataGetPublicISOs() : array
	{
		return json_decode('
			{
			  "public_isos": [
				{
				  "id": "cb676a46-66fd-4dfb-b839-443f2e6c0b604",
				  "name": "CentOS 7",
				  "description": "7 x86_64 Minimal",
				  "md5sum": "7f4df50f42ee1b52b193e79855a3aa19"
				}
			  ],
			  "meta": {
				"total": 1,
				"links": {
				  "next": "",
				  "prev": ""
				}
			  }
			}
		', true);
	}

	public function dataCreateISO() : array
	{
		return json_decode('
			{
				"iso": {
					"id": "cb676a46-66fd-4dfb-b839-443f2e6c0b60",
					"date_created": "2020-10-10T01:56:20+00:00",
					"filename": "my-iso.iso",
					"status": "pending"
				}
			}
		', true);
	}
}
