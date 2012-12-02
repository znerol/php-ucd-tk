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

    $set = $srv->getSet();
    $run = $srv->getRunnerService();

    foreach ($this->commands as $command) {
      $tmp = $run->run($command);
      $tmp = $set->difference($tmp, $result);
      $result = $set->union($result, $tmp);
    }

    return $result;
  }
}
