<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Billing\Bill;
use Vultr\VultrPhp\Services\Billing\BillingException;
use Vultr\VultrPhp\Services\Billing\Invoice;
use Vultr\VultrPhp\Services\Billing\InvoiceItem;
use Vultr\VultrPhp\Tests\VultrTest;

class BillingTest extends VultrTest
{
	public function testGetBillingHistory()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new Bill(), $client->billing->getBillingHistory(), $data);

		$this->expectException(BillingException::class);
		$client->billing->getBillingHistory();
	}

	public function testGetInvoices()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$this->testListObject(new Invoice(), $client->billing->getInvoices(), $data);

		$this->expectException(BillingException::class);
		$client->billing->getInvoices();
	}

	public function testGetInvoice()
	{
		$data = $this->getDataProvider()->getData();
		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		$id = 123456;
		$invoice = $client->billing->getInvoice($id);
		$this->testGetObject(new Invoice(), $invoice, $data);
		$this->expectException(BillingException::class);
		$client->billing->getInvoice($id);
	}

	public function testGetInvoiceItems()
	{
		$data = $this->getDataProvider()->getData();

		$client = $this->getDataProvider()->createClientHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode($data)),
			new Response(400, [], json_encode(['error' => 'Bad Request'])),
		]);

		foreach ($client->billing->getInvoiceItems(123456) as $item)
		{
			$this->assertInstanceOf(InvoiceItem::class, $item);
			foreach ($data[$item->getResponseListName()] as $object)
			{
				if ($object['unit_price'] !== $item->getUnitPrice()) continue;

				foreach ($item->toArray() as $prop => $prop_val)
				{
					$this->assertEquals($prop_val, $object[$prop], "Prop {$prop} failed");
				}
				break;
			}
		}

		$this->expectException(BillingException::class);
		$client->billing->getInvoiceItems(123456);
	}
}
