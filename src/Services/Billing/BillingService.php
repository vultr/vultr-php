<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class BillingService extends VultrService
{
	/**
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return Bill[]
	 */
	public function getBillingHistory(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/history', new Bill(), $options);
	}

	/**
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return Invoices[]
	 */
	public function getInvoices(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/invoices', new Invoice(), $options);
	}

	/**
	 * @param $invoice_id - int
	 * @throws BillingException
	 * @return Invoice
	 */
	public function getInvoice(int $invoice_id) : Invoice
	{
		return $this->getObject('billing/invoices/'.$invoice_id, new Invoice());
	}

	/**
	 * @param $invoice_id - int
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return InvoiceItem[]
	 */
	public function getInvoiceItems(int $invoice_id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects("billing/invoices/{$invoice_id}/items", new InvoiceItem(), $options);
	}
}
