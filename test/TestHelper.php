<?php

require_once __DIR__ . '/../src/bootstrap.php';


class ClockFake extends Clock
{
  private $seconds = 0;

  public function now()
  {
    return $this->seconds;
  }

  public function add($seconds)
  {
    $this->seconds += $seconds;
  }
}