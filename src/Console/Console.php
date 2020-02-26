<?php

namespace Interstellar\Console;

use Interstellar\Console\Command;
use Interstellar\Console\Help;
/**
* @author Julien GIDEL
*/
class Console
{

  /**
  * @var array
  */
  private $commands;

  public function __construct() {
    $this->commands= [];
    $this->createHelp();
  }

  public function createCommand(string $name, Action $action) {
    if($this->isCommandAllreadyExists($name)) {
      throw new InvalidArgumentException("Le nom de la commande existe déjà.");
    }

    $nCommand = new Command($name, $action);
    $this->commands[] = $nCommand;
    $this->updateHelp();
    return $nCommand;
  }

  private function isCommandAllreadyExists($name) {
    foreach($this->commands as $command) {
      if($command->getName() == $name) {
        return true;
      }
    }
    return false;
  }

  private function findCommand($name) {
    foreach($this->commands as $command) {
      if($command->getName() == $name) {
        return $command;
      }
    }
    throw new \InvalidArgumentException('Cette commande n\'existe pas.');
  }

  public function execute() {
    $command_to_execute = $this->findCommand($GLOBALS['argv'][1]);
    $command_to_execute->execute();
  }

  private function createHelp() {
    $this->createCommand('help', new Help())
         ->description('Permet de voir l\'aide des différentes commandes avec les options')
         ->addOption('commande', '-c', 'La commande specifique dont vous souhaitez l\'aide', 1, Command::N_REQUIRED)
         ->addOption('all', '-a', 'Affiche toutes les aides disponibles', 0, Command::N_REQUIRED);
  }

  private function updateHelp() {
    $this->findCommand('help')->getAction()->give($this->commands);
  }

}
