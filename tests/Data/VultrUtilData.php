<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Util\Model;

class VultrUtilData extends Model
{
	protected string $id;
	protected string $value1;
	protected int $value2;
	protected float $value3;
	protected array $value4;
	protected bool $value5;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getValue1() : string
	{
		return $this->value1;
	}

	public function setValue1(string $value1) : void
	{
		$this->value1 = $value1;
	}

	public function getValue2() : int
	{
		return $this->value2;
	}

	public function setValue2(int $value2) : void
	{
		$this->value2 = $value2;
	}

	public function getValue3() : float
	{
		return $this->value3;
	}

	public function setValue3(float $value3) : void
	{
		$this->value3 = $value3;
	}

	public function getValue4() : array
	{
		return $this->value4;
	}

	public function setValue4(array $value4) : void
	{
		$this->value4 = $value4;
	}

	public function getValue5() : bool
	{
		return $this->value5;
	}

	public function setValue5(bool $value5) : void
	{
		$this->value5 = $value5;
	}
}
