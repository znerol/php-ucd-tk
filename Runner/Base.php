<?php

namespace Znerol\Unidata\Runner;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;

class Base implements Runner
{
  public function __construct(CommandServices $srv) {
    $this->srv = $srv;
  }

  public function run(Command $command) {
    return $command->run($this, $this->srv);
  }
}
