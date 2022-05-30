<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Data;

use Vultr\VultrPhp\Tests\DataProvider;

class BillingData extends DataProvider
{
	protected function dataGetBillingHistory() : array
	{
		return json_decode('
			{
			  "billing_history": [
				{
				  "id": 123456,
				  "date": "2020-10-10T01:56:20+00:00",
				  "type": "invoice",
				  "description": "Invoice #123456",
				  "amount": 100.03,
				  "balance": 79.48
				},
				{
				  "id": 123457,
				  "date": "2020-10-10T01:46:05+00:00",
				  "type": "credit",
				  "description": "Account Credit",
				  "amount": 50.55,
				  "balance": -20.55
				},
				{
				  "id": 12345123,
				  "date": "2020-10-10T01:46:05+00:00",
				  "type": "credit",
				  "description": "Account Credit",
				  "amount": 50.55,
				  "balance": -20.55
				}
			  ],
			  "meta": {
				"total": 3,
				"links": {
				  "next": "",
				  "prev": ""
				}
			  }
			}
		', true);
	}

	protected function dataGetInvoices() : array
	{
		return json_decode('
			{
			  "billing_invoices": [
				{
				  "id": 123456,
				  "date": "2021-10-10T00:00:00+00:00",
				  "description": "Invoice #123456",
				  "amount": 5.25,
				  "balance": 10.25
				},
				{
				  "id": 1234567,
				  "date": "2021-10-10T00:00:00+00:00",
				  "description": "Invoice #123456",
				  "amount": 5.25,
				  "balance": 10.25
				},
				{
				  "id": 1234568,
				  "date": "2021-10-10T00:00:00+00:00",
				  "description": "Invoice #123456",
				  "amount": 5.25,
				  "balance": 10.25
				}
			  ],
			  "meta": {
				"total": 3,
				"links": {
				  "next": "",
				  "prev": ""
				}
			  }
			}
		', true);
	}
}
