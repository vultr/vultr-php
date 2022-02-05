<?php 

namespace Vultr\VultrPhp\Regions;

use Vultr\VultrPhp\Util\Model;

class Region extends Model 
{
  protected int $id;
  protected string $city;
  protected string $country;
  protected string $continent;
  protected array $options = array();

  public function getId() : int
  {
    return $this->id;
  }

  public function setId(int $id) : void
  {
    $this->id = $id;
  }

  public function getCity()  : string
  {
    return $this->city;
  }

  public function setCity(string $city) : void
  {
    $this->city = $city;
  } 

  public function getCountry() : string
  {
    return $this->country;
  }

  public function setCountry(string $country) : void
  {
    $this->country = $country;
  }

  public function getContinent() : string
  {
    return $this->continent;
  }

  public function setContinent(string $continent) : void
  {
    $this->continent = $continent;
  } 

  public function getOptions() : array
  {
    return $this->options;
  }
  
  public function setOptions($option) : void 
  {
    $this->options[] = $option;
  }

}