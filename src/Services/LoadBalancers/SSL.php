<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\LoadBalancers;

use Vultr\VultrPhp\Util\Model;

class SSL extends Model
{
	protected string $privateKey;
	protected string $certificate;
	protected string $chain;

	public function getPrivateKey() : string
	{
		return $this->privateKey;
	}

	public function setPrivateKey(string $private_key) : void
	{
		$this->privateKey = $private_key;
	}

	public function getCertificate() : string
	{
		return $this->certificate;
	}

	public function setCertificate(string $certificate) : void
	{
		$this->certificate = $certificate;
	}

	public function getChain() : string
	{
		return $this->chain;
	}

	public function setChain(string $chain) : void
	{
		$this->chain = $chain;
	}
}
