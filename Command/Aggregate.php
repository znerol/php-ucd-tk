<?php
/**
 * @file
 * Definition of Znerol::Unidata::Command::Aggregate
 */

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;

/**
 * Implements a Command capable of aggregating the results of an array of
 * subcommands. Every subcommand must return an array of
 * Znerol::Unidata::Uniprop objects. Using this command it is possible to
 * essentially implement the algorithm used to calculate derived properties as
 * described in the [Precis Framework] or [RFC5892].
 *
 * [Precis Framework]:
 *   http://tools.ietf.org/html/draft-ietf-precis-framework-06#section-7
 *   "Precis Framework IETF Draft 6 - Section 7"
 * [RFC5892]:
 *   http://tools.ietf.org/html/rfc5892#section-3
 *   "RFC5892 - Section 3"
 */
class Aggregate implements Command
{
  /**
   * List of instances of Znerol::Unidata::Command
   */
  private $commands;

  /**
   * Construct new Aggregate Command.
   *
   * @param array $commands
   *   List of commands. Each of them must result in an array of Uniprop
   *   instances.
   */
  public function __construct($commands) {
    $this->commands = $commands;
  }

  /**
   * Execute all commands and aggregate their results into a single array of
   * Uniprop instances.
   *
   * @retval array
   *   List of Uniprop instances.
   */
  public function run(Runner $runner, CommandServices $srv) {
    $result = array();

    $set = $srv->getSet();

    foreach ($this->commands as $command) {
      $tmp = $runner->run($command);
      $tmp = $set->difference($tmp, $result);
      $result = $set->union($result, $tmp);
    }

    return $result;
  }
}
