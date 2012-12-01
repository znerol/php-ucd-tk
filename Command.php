<?php

namespace Znerol\Unidata;

interface Command
{
  /**
   * Execute a command once and return its results.
   */
  public function run(CommandServices $srv);
}
