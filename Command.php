<?php
/**
 * @file
 * Definition of Znerol\Unidata\Command
 */

namespace Znerol\Unidata;

/**
 * Interface for command implementations.
 *
 * Commands are used throughout the unicode data toolkit to provide a
 * consistent interface for methods capable of reading and transforming data
 * tables. In order to allow caching of command-results, implementations must
 * store configuration related values in instance variables and may not record
 * any state information. This means that after construction, an object and its
 * members respectively may not be modified anymore.
 */
interface Command
{
  /**
   * Execute a command once and return its results
   *
   * This method may not modify the object, i.e. it may not add or alter any
   * instance variables.
   */
  public function run(Runner $caller, CommandServices $srv);
}
