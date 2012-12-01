<?php

namespace Znerol\Unidata\Runner;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;

class Base implements Runner
{
  public function openURL($url) {
    return fopen($url, "r");
  }

  public function run(Command $command, CommandServices $srv) {
    return $command->run($srv);
  }
}
