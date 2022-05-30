<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Tests\Suite;

use GuzzleHttp\Psr7\Response;
use Vultr\VultrPhp\Services\Billing\Bill;
use Vultr\VultrPhp\Services\Billing\BillingException;
use Vultr\VultrPhp\Services\Billing\Invoice;
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
		$this->markTestSkipped('Incomplete');
	}

	public function testGetInvoiceItems()
	{
		$this->markTestSkipped('Incomplete');
	}
}
