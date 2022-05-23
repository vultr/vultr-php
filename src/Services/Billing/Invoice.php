<?php

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Util\Model;

class Invoice extends Model
{
	protected int $id;
	protected string $date;
	protected string $description;
	protected float $amount;
	protected float $balance;

	public function getId() : int
	{
		return $this->id;
	}

	public function setId(int $id) : void
	{
		$this->id = $id;
	}

	public function getDate() : string
	{
		return $this->date;
	}

	public function setDate(string $date) : void
	{
		$this->date = $date;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getAmount() : float
	{
		return $this->amount;
	}

	public function setAmount(float $amount) : void
	{
		$this->amount = $amount;
	}

	public function getBalance() : float
	{
		return $this->balance;
	}

	public function setBalance(float $balance) : void
	{
		$this->balanace = $balance;
	}

	public function getResponseName() : string
	{
		return 'billing_invoice';
	}
}
