<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class ApplicationsData extends DataProvider
{
	protected function dataGetApplications() : array
	{
		return [
			'applications' => [
				[
					'id'          =>  1,
					'name'        =>  'LEMP',
					'short_name'  =>  'lemp',
					'deploy_name' =>  'LEMP on CentOS 6 x64',
					'type'        =>  'one-click',
					'vendor'      =>  'vultr',
					'image_id'    =>  ''
				],
				[
					'id'          => 1028,
					'name'        => 'OpenLiteSpeed WordPress',
					'short_name'  => 'openlitespeedwordpress',
					'deploy_name' => 'OpenLiteSpeed WordPress on Ubuntu 20.04 x64',
					'type'        => 'marketplace',
					'vendor'      => 'LiteSpeed_Technologies',
					'image_id'    => 'openlitespeed-wordpress'
				],
			],
			'meta' => [
				'total' => 2,
				'links' => [
					'next' => 'next',
					'prev' => 'prev',
				]
			]
		];
	}

	protected function dataGetApplicationsFilterOneClick() : array
	{
		return [
			'applications' => [
				[
					'id'          =>  1,
					'name'        =>  'LEMP',
					'short_name'  =>  'lemp',
					'deploy_name' =>  'LEMP on CentOS 6 x64',
					'type'        =>  'one-click',
					'vendor'      =>  'vultr',
					'image_id'    =>  ''
				],
			],
			'meta' => [
				'total' => 1,
				'links' => [
					'next' => 'next',
					'prev' => 'prev',
				]
			]
		];
	}

	protected function dataGetApplicationsFilterMarketplace() : array
	{
		return [
			'applications' => [
				[
					'id'          => 1028,
					'name'        => 'OpenLiteSpeed WordPress',
					'short_name'  => 'openlitespeedwordpress',
					'deploy_name' => 'OpenLiteSpeed WordPress on Ubuntu 20.04 x64',
					'type'        => 'marketplace',
					'vendor'      => 'LiteSpeed_Technologies',
					'image_id'    => 'openlitespeed-wordpress'
				],
			],
			'meta' => [
				'total' => 1,
				'links' => [
					'next' => 'next',
					'prev' => 'prev',
				]
			]
		];
	}

	protected function dataGetApplication() : array
	{
		return $this->dataGetApplications();
	}
}
