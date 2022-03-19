<?php

namespace Vultr\VultrPhp\Services\Users;

use Vultr\VultrPhp\Util\Model;

class User extends Model
{
	public const ACL_ABUSE = 'abuse';
	public const ACL_ALERTS = 'alerts';
	public const ACL_BILLING = 'billing';
	public const ACL_DNS = 'dns';
	public const ACL_FIREWALL = 'firewall';
	public const ACL_LOADBALANCER = 'loadbalancer';
	public const ACL_MANAGE_USERS = 'manage_users';
	public const ACL_OBJSTORE = 'objstore';
	public const ACL_PROVISIONING = 'provisioning';
	public const ACL_SUBSCRIPTIONS = 'subscriptions';
	public const ACL_SUBS_VIEW = 'subscriptions_view';
	public const ACL_SUPPORT = 'support';
	public const ACL_UPGRADE = 'upgrade';

	public const ACLS = [
		self::ACL_ABUSE,
		self::ACL_ALERTS,
		self::ACL_BILLING,
		self::ACL_DNS,
		self::ACL_FIREWALL,
		self::ACL_LOADBALANCER,
		self::ACL_MANAGE_USERS,
		self::ACL_OBJSTORE,
		self::ACL_PROVISIONING,
		self::ACL_SUBSCRIPTIONS,
		self::ACL_SUBS_VIEW,
		self::ACL_SUPPORT,
		self::ACL_UPGRADE
	];

	protected string $id;
	protected string $name;
	protected string $email;
	protected bool $apiEnabled;
	protected array $acls;

	public function getId() : string
	{
		return $this->id;
	}

	public function setId(string $id) : void
	{
		$this->id = $id;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function setEmail(string $email) : void
	{
		$this->email = $email;
	}

	public function getApiEnabled() : bool
	{
		return $this->apiEnabled;
	}

	public function setApiEnabled(bool $api_enabled) : void
	{
		$this->apiEnabled = $api_enabled;
	}

	public function getAcls() : array
	{
		return $this->acls;
	}

	public function setAcls(array $acls) : void
	{
		$this->acls = $acls;
	}

	public function getUpdateParams() : array
	{
		return ['email', 'name', 'password', 'api_enabled', 'acls'];
	}
}
