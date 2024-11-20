<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Services\VultrServiceException;
use Vultr\VultrPhp\Util\ListOptions;

/**
 * Billing service handler, for billing endpoints.
 *
 * @see https://www.vultr.com/api/#tag/billing
 */
class BillingService extends VultrService
{
	/**
	 * Retrieve the billing history of the account.
	 *
	 * @see https://www.vultr.com/api/#operation/list-billing-history
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return Bill[]
	 */
	public function getBillingHistory(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/history', new Bill(), $options);
	}

	/**
	 * Retrieve the invoices of the account.
	 *
	 * @see https://www.vultr.com/api/#operation/list-invoices
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return Invoices[]
	 */
	public function getInvoices(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/invoices', new Invoice(), $options);
	}

	/**
	 * Get a specific invoice based on its id.
	 *
	 * @see https://www.vultr.com/api/#operation/get-invoice
	 * @param $invoice_id - int
	 * @throws BillingException
	 * @return Invoice
	 */
	public function getInvoice(int $invoice_id) : Invoice
	{
		return $this->getObject('billing/invoices/'.$invoice_id, new Invoice());
	}

	/**
	 * Get invoice items to help drill into why the cost is what it is.
	 *
	 * @see https://www.vultr.com/api/#operation/get-invoice-items
	 * @param $invoice_id - int
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws BillingException
	 * @return InvoiceItem[]
	 */
	public function getInvoiceItems(int $invoice_id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects("billing/invoices/{$invoice_id}/items", new InvoiceItem(), $options);
	}

	/**
	 * Retrieve the pending charges of all the service types of the account.
	 *
	 * @see https://www.vultr.com/api/#operation/pending-charges
	 * @throws BillingException
	 * @throws VultrServiceException
	 * @return PendingCharge[]
	 */
	public function getPendingCharges() : array
	{
		return $this->getNonPaginatedListObjects('billing/pending-charges', new PendingCharge());
	}
}
