<?php

/**
* @author Julien GIDEL
*/
require_once __DIR__ . '/vendor/autoload.php';

use ezcli\Console\Console;
use ezcli\Console\Command;
use ezcli\Console\MyAction;

$console = new Console();
$console->createCommand('example', new MyAction())
        ->description('Exemple de commande')
        ->addOption('directory', '-d', 'This is the directry', 1, Command::REQUIRED)
        ->addOption('all', '-a', 'All option', 0, Command::N_REQUIRED);
$console->execute();

//You can now run "php exemple.php -d 'src' "
//Also, there is a "php exemple.php help -a"
