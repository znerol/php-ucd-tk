<?php

namespace Znerol\Unidata;

interface Runner
{
  /**
   * Run a command and return the result.
   */
  public function run(Command $command);
}
