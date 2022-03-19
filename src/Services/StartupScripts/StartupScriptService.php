<?php

namespace Vultr\VultrPhp\Services\StartupScripts;

use Vultr\VultrPhp\Services\VultrServiceException;
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

		try
		{
			$response = $this->post('startup-scripts', $params);
		}
		catch (VultrServiceException $e)
		{
			throw new StartupScriptException('Failed to create a startup script: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}

		return VultrUtil::convertJSONToObject($response->getBody(), new StartupScript(), 'startup_script');
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
		try
		{
			$this->delete('startup-scripts/'.$startup_id);
		}
		catch (VultrServiceException $e)
		{
			throw new StartupScriptException('Failed to delete startup script: '.$e->getMessage(), $e->getHTTPCode(), $e);
		}
	}
}

