<?php

namespace Znerol\Unidata\Runner;
use Znerol\Unidata\Command;

/**
 * Simple runner implementation capable of caching the results of commands in 
 * memory.
 */
class Caching extends Base {
  private $results = array();

  public function run(Command $command) {
    $key = sha1(var_export($command, true));

    if (!isset($this->results[$key])) {
      $this->results[$key] = parent::run($command);
    }

    return $this->results[$key];
  }
}
