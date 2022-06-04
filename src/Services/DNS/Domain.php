<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\DNS;

use Vultr\VultrPhp\Util\Model;

class Domain extends Model
{
	protected string $domain;
	protected string $dateCreated;
	protected string $dnsSec;

	public function getDomain() : string
	{
		return $this->domain;
	}

	public function setDomain(string $domain) : void
	{
		$this->domain = $domain;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $date_created) : void
	{
		$this->dateCreated = $date_created;
	}

	public function getDnsSec() : string
	{
		return $this->dnsSec;
	}

	public function setDnsSec(string $dns_sec) : void
	{
		$this->dnsSec = $dns_sec;
	}

	public function getModelExceptionClass() : string
	{
		return str_replace('DomainException', 'DNSException', parent::getModelExceptionClass());
	}
}

