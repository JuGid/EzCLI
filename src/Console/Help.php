<?php

namespace Interstellar\Console;

use Interstellar\Console\Action;

class Help extends Action
{
  public function execute() {
    if($this->exists('all')) {
      $this->printAll();
      return;
    }

    if($this->exists('commande')) {
      $this->print($this->getOptionValue('commande'));
      return;
    }

    echo "Pour afficher l'aide de help utilisez la command help -a";

  }

  private function printCommand($command) {
    echo $command->getName()."\n\r";
    foreach($command->getSafeOptions() as $option=>$values) {
      $strCmd = "\t".$option."\t args[".$values['argumentsNeeded']."]\t".$values['description'];

      if($values['isRequired']) {
        $strCmd .="\t[required]";
      }

      echo $strCmd ."\n";
    }
  }

  private function printAll() {
    foreach($this->getOutValues() as $command) {
      $this->printCommand($command);
    }
  }

  private function print($command_name) {
    foreach($this->getOutValues() as $command) {
      if($command->getName() == $command_name) {
        $this->printCommand($command);
        return;
      }
    }
    echo "Cette commande n'existe pas.\n\r";
  }
}
