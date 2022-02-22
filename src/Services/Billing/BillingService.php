<?php

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class BillingService extends VultrService
{
	private static ?array $cache_bill = null;

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws BillingException
	 * @return Bill[]
	 */
	public function getBills(?ListOptions &$options = null) : array
	{
		$bills = [];
		if ($options === null)
		{
			$options = new ListOptions(100);
		}

		try
		{
			$bills = $this->list('bills', new Bill(), $options);
		}
		catch (VultrServiceException $e)
		{
			throw new BillException("Failed to get bills: " .$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return $bills;
	}

	/**
	 * @param $id - string - Ex ewr - Bill id.
	 * @throws BillingException
	 * @return Bill|null
	 */
	public function getBill(string $id) : ?Bill
	{
		$this->cacheBills();
		return static::$cache_bill[$id] ?? null;
	}

	/**
	 * @param $override - bool - Depending on whether to requery the bills.
	 * @throws BillingException
	 * @return void
	 */
	public function cacheBills(bool $override = false) : void
	{
		if (static::$cache_bill !== null && !$override)
		{
			return;
		}

		static::$cache_bill = [];
		$options = new ListOptions(500);
		foreach ($this->getBills($options) as $bill)
		{
			static::$cache_bill[$bill->getId()] = $bill;
		}
	}
}
