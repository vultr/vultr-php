<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\VultrClientException;

class DNSService extends VultrService
{
	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/list-dns-domains
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws DNSException
	 * @return Domain[]
	 */
	public function getDomains(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('domains', new Domain(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/get-dns-domain
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
	 * @see https://www.vultr.com/api/#tag/dns/operation/delete-dns-domain
	 * @param $domain - string - Example: example.com
	 * @throws DNSException
	 * @return void
	 */
	public function deleteDomain(string $domain) : void
	{
		$this->deleteObject('domains/'.$domain, new Domain());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/update-dns-domain
	 * @throws DNSException
	 * @return void
	 */
	public function updateDomain(Domain $domain) : void
	{
		$this->patchObject('domains/'.$domain->getDomain(), $domain);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/get-dns-domain-soa
	 * @param $domain - string - Example: example.com
	 * @throws DNSException
	 * @return DNSSOA
	 */
	public function getSOAInfo(string $domain) : DNSSOA
	{
		return $this->getObject('domains/'.$domain.'/soa', new DNSSOA());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/update-dns-domain-soa
	 * @param $domain - string - Example: example.com
	 * @param $soa - DNSSOA
	 * @throws DNSException
	 * @return void
	 */
	public function updateSOAInfo(string $domain, DNSSOA $soa) : void
	{
		$this->patchObject('domains/'.$domain.'/soa', $soa);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/get-dns-domain-dnssec
	 * @param $domain - string - Example: example.com
	 * @throws DNSException
	 * @throws VultrException
	 * @return array
	 */
	public function getDNSSecInfo(string $domain) : array
	{
		$client = $this->getClientHandler();

		try
		{
			$response = $client->get('domains/'.$domain.'/dnssec');
		}
		catch (VultrClientException $e)
		{
			throw new DNSException('Failed to get dns sec information: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::decodeJSON((string)$response->getBody(), true)['dns_sec'];
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/create-dns-domain-record
	 * @param $domain - string - Example: example.com
	 * @param $record - Record
	 * @throws DNSException
	 * @return Record
	 */
	public function createRecord(string $domain, Record $record) : Record
	{
		return $this->createObject('domains/'.$domain.'/records', new Record(), $record->getInitializedProps());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/create-dns-domain-record
	 * @param $domain - string - Example: example.com
	 * @param $options - ListOptions|null - Interact via reference.
	 * @throws DNSException
	 * @return array
	 */
	public function getRecords(string $domain, ?ListOptions &$options = null) : array
	{
		return $this->getListObjects('domains/'.$domain.'/records', new Record(), $options);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/get-dns-domain-record
	 * @param $domain - string - Example: example.com
	 * @param $record_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws DNSException
	 * @return array
	 */
	public function getRecord(string $domain, string $record_id) : Record
	{
		return $this->getObject('domains/'.$domain.'/records/'.$record_id, new Record());
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/update-dns-domain-record
	 * @param $domain - string - Example: example.com
	 * @param $record - Record - Fully initialized object.
	 * @throws DNSException
	 * @return void
	 */
	public function updateRecord(string $domain, Record $record) : void
	{
		$this->patchObject('domains/'.$domain.'/records/'.$record->getId(), $record);
	}

	/**
	 * @see https://www.vultr.com/api/#tag/dns/operation/delete-dns-domain-record
	 * @param $domain - string - Example: example.com
	 * @param $record_id - string - Example: cb676a46-66fd-4dfb-b839-443f2e6c0b60
	 * @throws DNSException
	 * @return void
	 */
	public function deleteRecord(string $domain, string $record_id) : void
	{
		$this->deleteObject('domains/'.$domain.'/records/'.$record_id, new Record());
	}
}
