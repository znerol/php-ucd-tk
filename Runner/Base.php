<?php

namespace Znerol\Unidata\Runner;
use Znerol\Unidata\Command;
use Znerol\Unidata\Runner;

class Base implements Runner
{
  public function openURL($url) {
    return fopen($url, "r");
  }

  public function run(Command $command) {
    return $command->run($this);
  }
}
