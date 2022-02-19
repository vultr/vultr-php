<?php

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Util\Model;

class Bill extends Model
{
    protected int $id;
    protected string $date;
    protected string $type;
    protected string $description;
    protected float $amount;
    protected float $balance;

    public function getId() : int
    {
        return $this->id;
    }

    public function setId(string $id) : void
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

    public function getType() : string
    {
        return $this->type;
    }

    public function setType(string $type) : void
    {
        $this->type = $type;
    }

    public function getDescription() : string
    {
        return $this->$description;
    }

    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    public function getAmount() : float
    {
        return $this->$amount;
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
        $this->balance = $balance;
    }
}
