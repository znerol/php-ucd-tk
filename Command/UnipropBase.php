<?php
/**
 * @file
 * Definition of Znerol::Unidata::Command::UnipropBase.
 */

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

/**
 * Abstract base class for parsers turning data from Command::ReadTable into an
 * array of Uniprop instances.
 */
abstract class UnipropBase implements Command
{
  /**
   * The reader Command. Typically an instance of Command::ReadTable.
   */
  private $reader;

  /**
   * Construct a new instance with the given reader.
   *
   * @param Reader $reader
   *   Instance of Command::ReadTable used to generate an array of plain UDC
   *   records.
   */
  public function __construct(Command $reader) {
    $this->reader = $reader;
  }

  /**
   * Construct Uniprop objects from UCD records retrieved by running the
   * `$reader` command.
   *
   * @retval array
   *   List of Uniprop objects
   */
  public function run(Runner $runner, CommandServices $srv) {
    $rows = $runner->run($this->reader);

    $extents = array();
    foreach ($rows as $row) {
      list($start, $end, $fields, $comment) = $row;
      $props = $this->getProps($start, $end, $fields, $comment);
      if (!empty($props)) {
        $comment = $this->getComment($start, $end, $fields, $comment);
        $next = ($end ?: $start) + 1;
        $extents[] = new Uniprop($start, $next, $props, $comment);
      }
    }

    return $srv->getSet()->union($extents);
  }

  /**
   * Extract and return properties array from a unicode table row.
   *
   * @retval array
   *   Key-Value pairs of properties associated to the given record or NULL if
   *   no Unidata object should be generated from this record.
   */
  protected abstract function getProps($start, $end, $fields, $comment);

  /**
   * Extract and return a comment.
   *
   * @retval string
   *   A string specifying the comment which shoud be used for new Unidata
   *   object or NULL.
   */
  protected function getComment($start, $end, $fields, $comment) {
    return $comment;
  }
}
