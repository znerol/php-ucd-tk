<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class Union Implements Command
{
  private $set;

  private $commands;

  public function __construct(Uniprop\Set $set, $commands) {
    $this->set = $set;
    $this->commands = $commands;
  }

  public function run(Runner $runner) {
    $result = array();

    foreach ($this->commands as $command) {
      $tmp = $runner->run($command);
      $result = $this->set->union($result, $tmp);
    }

    return $result;
  }
}
