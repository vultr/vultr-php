<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use stdClass;
use Vultr\VultrPhp\Util\ModelOptions;

class ModelOptionsData extends ModelOptions
{
	protected string $string = '';
	protected int $int = 2;
	protected float $float = 2.2;
	protected array $array = [];
	protected bool $bool = true;
	protected ?stdClass $object = null;
}
