<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;

class Union Implements Command
{
  private $commands;

  public function __construct($commands) {
    $this->commands = $commands;
  }

  public function run(CommandServices $srv) {
    $result = array();

    $runner = $srv->getRunner();

    foreach ($this->commands as $command) {
      $tmp = $runner->run($command);
      $result = $this->set->union($result, $tmp);
    }

    return $result;
  }
}
