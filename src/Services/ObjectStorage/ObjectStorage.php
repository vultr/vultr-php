<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\ObjectStorage;

use Vultr\VultrPhp\Util\Model;

class ObjectStorage extends Model
{
	protected string $id;
	protected string $dateCreated;
	protected int $clusterId;
	protected string $region;
	protected string $location;
	protected string $label;
	protected string $status;
	protected string $s3Hostname;
	protected string $s3AccessKey;
	protected string $s3SecretKey;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getDateCreated() : string
	{
		return $this->dateCreated;
	}

	public function setDateCreated(string $date_created) : void
	{
		$this->dateCreated = $date_created;
	}

	public function getClusterId() : int
	{
		return $this->clusterId;
	}

	public function setClusterId(int $cluster_id) : void
	{
		$this->clusterId = $cluster_id;
	}

	public function getRegion() : string
	{
		return $this->region;
	}

	public function setRegion(string $region) : void
	{
		$this->region = $region;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	public function getLocation() : string
	{
		return $this->location;
	}

	public function setLocation(string $location) : void
	{
		$this->location = $location;
	}

	public function getStatus() : string
	{
		return $this->status;
	}

	public function setStatus(string $status) : void
	{
		$this->status = $status;
	}

	public function getS3Hostname() : string
	{
		return $this->s3Hostname;
	}

	public function setS3Hostname(string $s3_hostname) : void
	{
		$this->s3Hostname = $s3_hostname;
	}

	public function getS3AccessKey() : string
	{
		return $this->s3AccessKey;
	}

	public function setS3AccessKey(string $s3_access_key) : void
	{
		$this->s3AccessKey = $s3_access_key;
	}

	public function getS3SecretKey() : string
	{
		return $this->s3SecretKey;
	}

	public function setS3SecretKey(string $s3_secret_key) : void
	{
		$this->s3SecretKey = $s3_secret_key;
	}

	public function getUpdateParams() : array
	{
		return ['label'];
	}
}
