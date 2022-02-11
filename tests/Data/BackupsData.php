<?php

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class BackupsData extends DataProvider
{
	protected function dataGetBackups() : array
	{
		return [
			'backups' => [
				[
					'id' => 'cb676a46-66fd-4dfb-b839-12312414',
					'date_created' => '2020-10-10T01:56:20+00:00',
					'description' => 'Example automatic backup',
					'size' => 5000000,
					'status' => 'complete'
				],
				[
					'id' => 'cb676a46-66fd-4dfb-b839-32525262',
					'date_created' => '2020-10-10T01:56:20+00:00',
					'description' => 'Example automatic backup',
					'size' => 0,
					'status' => 'pending'
				],
			],
			'meta' => [
				'total' => 2,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];
	}

	protected function dataGetBackupsByInstanceId(string $id) : array
	{
		return [
			'backups' => [
				[
					'id' => 'cb676a46-66fd-4dfb-b839-32525262',
					'date_created' => '2020-10-10T01:56:20+00:00',
					'description' => 'Example automatic backup',
					'size' => 0,
					'status' => 'pending'
				],
			],
			'meta' => [
				'total' => 1,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];
	}

	protected function dataGetBackup(string $id) : array
	{
		return [
			'backup' => [
				'id' => $id,
				'date_created' => '2020-10-10T01:56:20+00:00',
				'description' => 'Example automatic backup',
				'size' => 5000000,
				'status' => 'complete'
			]
		];
	}
}
