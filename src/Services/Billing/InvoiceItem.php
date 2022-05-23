<?php

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Util\Models;

class InvoiceItem extends Model
{
	protected string $description;
	protected string $product;
	protected string $startDate;
	protected string $endDate;
	protected int $units;
	protected string $unitType;
	protected float $unitPrice;
	protected int $total;

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getProduct() : string
	{
		return $this->product;
	}

	public function setProduct(string $product) : void
	{
		$this->product = $product;
	}

	public function getStartDate() : string
	{
		return $this->startDate;
	}

	public function setStartDate(string $start_date) : void
	{
		$this->startDate = $start_date;
	}

	public function getEndDate() : string
	{
		return $this->endDate;
	}

	public function setEndDate(string $end_date) : void
	{
		$this->endDate = $end_date;
	}

	public function getUnits() : int
	{
		return $this->units;
	}

	public function setUnits(int $units) : void
	{
		$this->units = $units;
	}

	public function getUnitType() : string
	{
		return $this->unitType;
	}

	public function setUnitType(string $unit_type) : void
	{
		$this->unitType = $unit_type;
	}

	public function getUnitPrice() : float
	{
		return $this->unitPrice;
	}

	public function setUnitPrice(float $unit_price) : void
	{
		$this->unitPrice = $unit_price;
	}

	public function getTotal() : int
	{
		return $this->total;
	}

	public function setTotal(int $total) : void
	{
		$this->total = $total;
	}

	public function getResponseName() : string
	{
		return 'invoice_item';
	}
}
