<?php 

namespace Vultr\VultrPhp\Applications;

use Vultr\VultrPhp\VultrException;
use Vultr\VultrPhp\VultrService;
use Vultr\VultrPhp\Util\VultrUtil;
use Vultr\VultrPhp\Util\ListOptions;

class RegionServices extends VultrService 
{
  /**
   * @param $per_page
   * @param $cursor
   */
  public function getRegions(?ListOptions &$options = null) : array
  {
    $regions = [];
    try 
    {
      if($options === null) 
      {
        $options = new ListOptions(50);
      }
      $regions = $this->getClient()->list('regions', new Region(), $options);
    }
    catch(VultrClientException $e) 
    {
      throw new RegionException("Failed to get regions: " .$e->getMessage(), $e->getHTTPCode());
    }
    return $regions;
  }
}