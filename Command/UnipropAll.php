<?php
/**
 * @file
 * Definition of Znerol::Unidata::Command::UnipropAll.
 */

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;

/**
 * Simple parser capable of transfroming the output of
 * Command::ReadTable into an array of
 * Uniprop instances.
 */
class UnipropAll extends UnipropBase
{
  /**
   * Property name used to construct the properties dictionary.
   */
  private $propname;

  /**
   * Optional: static comment string.
   */
  private $comment;

  /**
   * Construct a new uniprop transformer command.
   *
   * @param Command $reader
   *   A Command generating output of the form like Command::ReadTable
   * @param string $propname
   *   The property to which the value from field 1 will be assigned to
   * @param string $comment
   *   (optional) A static string which should be used as the comment of newly
   *   created Uniprop objects. If not given, the comment parsed from the
   *   source file is used.
   */
  public function __construct(Command $reader, $propname, $comment = NULL) {
    parent::__construct($reader);
    $this->propname = $propname;
    $this->comment = $comment;
  }

  protected function getProps($start, $end, $fields, $comment) {
    return array($this->propname => $fields[1]);
  }

  protected function getComment($start, $end, $fields, $comment) {
    return is_string($this->comment) ? $this->comment :
      parent::getComment($start, $end, $fields, $comment);
  }
}
