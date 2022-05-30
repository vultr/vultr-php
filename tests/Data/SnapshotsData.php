<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class SnapshotsData extends DataProvider
{
	protected function dataGetSnapshots() : array
	{
		return [
			'snapshots' => [
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e141410',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 215,
					'app_id'          => 0
				],
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e6c0b60',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot2',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 0,
					'app_id'          => 25
				],
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f214d10b604',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'snapshots go brrrr',
					'size'            => 0,
					'compressed_size' => 0,
					'status'          => 'pending',
					'os_id'           => 0,
					'app_id'          => 0
				],
			],
			'meta' => [
				'total' => 3,
				'links' => [
					'next' => '',
					'prev' => ''
				]
			]
		];
	}

	protected function dataGetSnapshotsFilter(string $description) : array
	{
		return [
			'snapshots' => [
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e141410',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 215,
					'app_id'          => 0
				],
				[
					'id'              => 'cb676a46-66fd-4dfb-b839-443f2e6c0b60',
					'date_created'    => '2020-10-10T01:56:20+00:00',
					'description'     => 'Example Snapshot2',
					'size'            => 42949672960,
					'compressed_size' => 949678560,
					'status'          => 'complete',
					'os_id'           => 0,
					'app_id'          => 25
				]
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

	protected function dataGetSnapshot(string $id) : array
	{
		return [
			'snapshot' => [
				'id'              => $id,
				'date_created'    => '2020-10-10T01:56:20+00:00',
				'description'     => 'Example Snapshot',
				'size'            => 42949672960,
				'compressed_size' => 949678560,
				'status'          => 'complete',
				'os_id'           => 215,
				'app_id'          => 0
			]
		];
	}

	protected function dataCreateSnapshot(string $id) : array
	{
		return [
			'snapshot' => [
				'id'              => $id,
				'date_created'    => '2020-10-10T01:56:20+00:00',
				'description'     => 'Example Snapshot',
				'size'            => 0,
				'compressed_size' => 0,
				'status'          => 'pending',
				'os_id'           => 215,
				'app_id'          => 0
			]
		];
	}

	protected function dataCreateSnapshotFromURL() : array
	{
		return $this->dataCreateSnapshot('cb676a46-66fd-4dfb-b839-443f2e6c0b60');
	}
}
