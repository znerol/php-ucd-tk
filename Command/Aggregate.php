<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;

class Aggregate implements Command 
{
  private $commands;

  public function __construct($commands) {
    $this->commands = $commands;
  }

  public function run(CommandServices $srv) {
    $result = array();

    $runner = $srv->getRunner();
    $set = $srv->getSet();

    foreach ($this->commands as $command) {
      $tmp = $runner->run($command);
      $tmp = $set->diff($tmp, $result);
      $result = $set->union($result, $tmp);
    }

    return $result;
  }
}
