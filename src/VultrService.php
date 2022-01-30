<?php

namespace Vultr\VultrPhp;

abstract class VultrService
{
	protected VultrClient $client;

	public function __construct(VultrClient $client)
	{
		$this->client = $client;
	}

	public function getClient() : VultrClient
	{
		return $this->client;
	}
}
