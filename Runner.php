<?php

namespace Znerol\Unidata;

interface Runner
{
  /**
   * Open an URL and return a stream object.
   */
  public function openURL($url);

  /**
   * Run a command and return the result.
   */
  public function run(Command $command);
}
