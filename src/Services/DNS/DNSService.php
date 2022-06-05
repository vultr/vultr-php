<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class DNSService extends VultrService
{
	/**
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws DNSException
	 * @return Domain[]
	 */
	public function getDomains(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('domains', new Domain(), $options);
	}

	/**
	 * @param $domain - string - Example: example.com
	 * @throws DNSException
	 * @return Domain
	 */
	public function getDomain(string $domain) : Domain
	{
		return $this->getObject('domains/'.$domain, new Domain());
	}

	/**
	 * @see https://www.vultr.com/api/#operation/create-dns-domain
	 * @param $domain - string - Example: example.com
	 * @param $dns_sec - string
	 * @param $ip - string
	 * @throws DNSException
	 * @return void
	 */
	public function createDomain(string $domain, string $dns_sec = 'disabled', string $ip = '')
	{
		$params = [
			'domain'  => $domain,
			'dns_sec' => $dns_sec
		];

		if ($ip != '')
		{
			$params['ip'] = $ip;
		}

		return $this->createObject('domains', new Domain(), $params);
	}

	/**
	 * @throws DNSException
	 * @return void
	 */
	public function deleteDomain(string $domain) : void
	{
		$this->deleteObject('domains/'.$domain, new Domain());
	}

	/**
	 * @throws DNSException
	 * @return void
	 */
	public function updateDomain(Domain $domain) : void
	{
		$this->patchObject('domains/'.$domain->getDomain(), $domain);
	}

	public function getSOAInfo() : DNSSOA
	{

	}

	public function updateSOAInfo() : void
	{

	}

	public function getDNSSecInfo() : array
	{

	}

	public function createRecord(string $domain, Record $record) : Record
	{

	}

	public function getRecords(string $domain) : array
	{

	}

	public function getRecord(string $domain, string $record_id) : Record
	{

	}

	public function updateRecord(string $domain, Record $record) : void
	{

	}

	public function deleteRecord(string $domain, string $record_id) : void
	{

	}
}
