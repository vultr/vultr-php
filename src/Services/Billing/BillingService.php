<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\Billing;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class BillingService extends VultrService
{
	public function getBillingHistory(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/history', new Bill(), $options);
	}

	public function getInvoices(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('billing/invoices', new Invoice(), $options);
	}

	public function getInvoice(string $invoice_id) : Invoice
	{
		return $this->getObject('billing/invoices/'.$invoice_id, new Invoice());
	}

	public function getInvoiceItems(string $invoice_id, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects("billing/invoices/{$invoice_id}/items", new InvoiceItem(), $options);
	}
}
