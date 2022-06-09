<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class ObjectStorageData extends DataProvider
{
	public function getObjectStoreSub() : array
	{
		return json_decode(file_get_contents(__DIR__.'/../json_responses/objectstorage/dataGetObjectStoreSub.json'), true);
	}
}
