<?php

namespace Notadd\Container;

class PayContainer
{
  private $drivers = [];

  public function __construct(string $name)
  {
      $this->drivers[$name] = $name;
  }
  public function driver(string $driver)
  {
      return $this->drivers[$driver];
  }
}
