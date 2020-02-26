<?php

namespace ezcli\Console;

/**
* @author Julien GIDEL
*/
abstract class Action
{
  protected $values;

  private $outValues;

  public function __construct() {
    $this->values = [];
    $this->outValues = [];
  }

  public function set($values) {
    $this->values = $values;
  }

  public function give($values) {
    $this->outValues = $values;
  }

  public function getOptionValue($option) {
    if(array_key_exists($option, $this->values)) {
      if(count($this->values[$option]) > 1) {
        return $this->values[$option];
      } else {
        return $this->values[$option][0];
      }
    }
    return null;
  }

  public function getOptions() {
    return $this->values;
  }

  public function exists($option) {
    return array_key_exists($option, $this->values);
  }

  protected function getOutValues() {
    return $this->outValues;
  }

  public abstract function execute();
}
