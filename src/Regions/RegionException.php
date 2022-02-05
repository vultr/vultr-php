<?php 

namespace Vultr\VultrPhp\Regions;

use Vultr\VultrPhp\VultrException;

class RegionException extends VultrException 
{
  public function __construct(string $message, ?int $http_code = null, ?Throwable $previous = null)
  {
    parent::__construct($message, VultrException::REGION_CODE, $previous, $http_code);
  }
}