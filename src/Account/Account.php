<?php

namespace Vultr\VultrPhp\Account;

use Vultr\VultrPhp\Util\Model;

class Account extends Model
{
	protected string $name;
	protected string $email;
	protected array $acls;
	protected float $balance;
	protected float $pendingCharges;
	protected string $lastPaymentDate;
	protected float $lastPaymentAmount;

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function setEmail(string $email) : void
	{
		$this->email = $email;
	}

	public function getAcls() : array
	{
		return $this->acls;
	}

	public function setAcls(array $acls) : void
	{
		$this->acls = $acls;
	}

	public function getBalance() : float
	{
		return $this->balance;
	}

	public function setBalance(float $balance) : void
	{
		$this->balance = $balance;
	}

	public function getPendingCharges() : float
	{
		return $this->pendingCharges;
	}

	public function setPendingCharges(float $pending_charges) : void
	{
		$this->pendingCharges = $pending_charges;
	}

	public function getLastPaymentDate() : string
	{
		return $this->lastPaymentDate;
	}

	public function setLastPaymentDate(string $last_payment_date) : void
	{
		$this->lastPaymentDate = $last_payment_date;
	}

	public function getLastPaymentAmount() : float
	{
		return $this->lastPaymentAmount;
	}

	public function setLastPaymentAmount(float $last_payment_amount) : void
	{
		$this->lastPaymentAmount = $last_payment_amount;
	}
}
