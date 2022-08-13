<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Vultr\VultrPhp\Util\Model;

/**
 * Holds SOA(start of authority) information.
 */
class DNSSOA extends Model
{
	protected string $nsprimary;
	protected string $email;

	public function getNsPrimary() : string
	{
		return $this->nsprimary;
	}

	public function setNsPrimary(string $nsprimary) : void
	{
		$this->nsprimary = $nsprimary;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function setEmail(string $email) : void
	{
		$this->email = $email;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('DNSSOAException', 'DNSException', parent::getModelExceptionClass());
	}

	public function getUpdateParams() : array
	{
		return ['nsprimary', 'email'];
	}

	public function getResponseName() : string
	{
		return 'dns_soa';
	}
}
