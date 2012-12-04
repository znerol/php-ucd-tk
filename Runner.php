<?php
/**
 * @file
 * Definition of Znerol::Unidata::Runner.
 */

namespace Znerol\Unidata;

/**
 * Interface for classes capable of executing Command instances.
 */
interface Runner
{
  /**
   * Run a command and return its result.
   *
   * @param Command $command
   *   The Command which will be executed.
   * @retval mixed
   *   The result returned by Command::run.
   */
  public function run(Command $command);
}
