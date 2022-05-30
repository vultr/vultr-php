<?php

declare(strict_types=1);

namespace Vultr\VultrPhp\Services\StartupScripts;

use Vultr\VultrPhp\Services\VultrService;
use Vultr\VultrPhp\Util\ListOptions;

class StartupScriptService extends VultrService
{
	/**
	 * @param $startup_id - string - UUID of the startup script
	 * @throws StartupScriptException
	 * @throws VultrException
	 * @return StartupScript
	 */
	public function getStartupScript(string $startup_id) : StartupScript
	{
		return $this->getObject('startup-scripts/'.$startup_id, new StartupScript());
	}

	/**
	 * @param $options - ListOptions - Interact via reference.
	 * @throws StartupScriptException
	 * @return StartupScript[]
	 */
	public function getStartupScripts(?ListOptions &$options = null) : array
	{
		return $this->getListObjects('startup-scripts', new StartupScript(), $options);
	}

	/**
	 * @param $script - StartupScript Model with any properties defined will be used in the response.
	 * @throws StartupScriptException
	 * @throws VultrException
	 * @return StartupScript
	 */
	public function createStartupScript(StartupScript $script) : StartupScript
	{
		$params = $script->toArray();
		foreach ($params as $attr => $param)
		{
			if (empty($param)) unset($params[$attr]);
		}

		return $this->createObject('startup-scripts', new StartupScript(), $params);
	}

	/**
	 * @param $script - StartupScript
	 * @throws StartupScriptException
	 * @return void
	 */
	public function updateStartupScript(StartupScript $script) : void
	{
		$this->patchObject('startup-scripts/'.$script->getId(), $script);
	}

	/**
	 * @param $startup_id - string
	 * @throws StartupScriptException
	 * @return void
	 */
	public function deleteStartupScript(string $startup_id) : void
	{
		$this->deleteObject('startup-scripts/'.$startup_id, new StartupScript());
	}
}

