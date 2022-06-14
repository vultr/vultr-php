<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\BareMetal;

use Vultr\VultrPhp\Util\ModelOptions;

abstract class BareMetalOptions extends ModelOptions
{
	public function getModelExceptionClass() : string
	{
		return 'Vultr\VultrPhp\Services\BareMetal\BareMetalException';
	}
}
