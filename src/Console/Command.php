<?php

namespace Interstellar\Console;

/**
* @author Julien GIDEL
*/
class Command
{
  const REQUIRED = 1;
  const N_REQUIRED = 0;

  /**
  * @var Action
  */
  private $action;

  /**
  * @var string
  */
  private $name;

  /**
  * @var string
  */
  private $description;

  /**
  * @var array
  */
  private $optionsLiterrals;

  /**
  * @var array
  */
  private $optionsValues;

  public function __construct(string $name, Action $action) {
    $this->action = $action;
    $this->name = $name;
    $this->optionsLiterrals = array();
    $this->optionsValues = array();
  }

  public function description(string $message) {
    $this->description = $message;
    return $this;
  }

  public function addOption($nLong, $nShort, $description, $nbArguments, $required) {
    if(!preg_match("/-[a-zA-Z]*/", $nShort)) {
      $nShort = '-'.$nShort;
    }

    if(!array_key_exists($nShort, $this->optionsLiterrals)) {
      $this->optionsLiterrals[$nShort] = array(
        'description'=>$description,
        'nLong'=>$nLong,
        'isRequired'=>$required,
        'argumentsNeeded'=>$nbArguments
      );
    }
    return $this;
  }

  public function execute() {
    global $argv, $argc;

    if($this->verifyRequiredOptions()) {
      return;
    }

    for($i=0; $i < $argc; $i++) {
      if(array_key_exists($argv[$i], $this->optionsLiterrals)) {
        $nValues = $this->optionsLiterrals[$argv[$i]]['argumentsNeeded'];
        $lName = $this->optionsLiterrals[$argv[$i]]['nLong'];
        $values = $this->addValues($argv, $i+1, $nValues);
        $this->optionsValues[$lName] = $values;
        $i += $nValues;
      }
    }

    $this->action->set($this->optionsValues);
    $this->action->execute();
  }

  private function verifyRequiredOptions() {
    global $argv;

    $isRequiredError = false;

    foreach($this->optionsLiterrals as $option=>$values) {
      if($values['isRequired'] && !array_search($option, $argv)) {
        $isRequiredError = true;
        echo "L'option ".$values['nLong']."[". $option ."] est marquée comme REQUIRED. Merci de la renseigner.\n";
      }
    }

    return $isRequiredError;
  }

  private function addValues($array, $begin, $length) {
    $values = array();

    if(($begin + $length - 1) > count($array) - 1) {
      return;
    }

    for($i=$begin; $i < ($begin + $length);$i++) {
      $values[] = $array[$i];
    }

    return $values;
  }

  public function getName() {
    return $this->name;
  }

  public function getAction() {
    return $this->action;
  }

  public function getSafeOptions() {
    return $this->optionsLiterrals;
  }
}
