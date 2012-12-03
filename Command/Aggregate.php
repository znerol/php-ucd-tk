<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;

class Aggregate implements Command 
{
  private $commands;

  public function __construct($commands) {
    $this->commands = $commands;
  }

  public function run(Runner $runner, CommandServices $srv) {
    $result = array();

    $set = $srv->getSet();

    foreach ($this->commands as $command) {
      $tmp = $runner->run($command);
      $tmp = $set->difference($tmp, $result);
      $result = $set->union($result, $tmp);
    }

    return $result;
  }
}
