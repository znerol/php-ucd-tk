<?php
/**
 * @file
 * Definition of Znerol::Unidata::Command::UnipropCallback.
 */

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;

/**
 * Simple parser capable of transfroming the output of Command::ReadTable into
 * an array of Uniprop instances using callback functions.
 */
class UnipropCallback extends UnipropBase
{
  /**
   * Callback function used to filter records and extract property values.
   */
  private $propcb;

  /**
   * Callback function used to extract or generate comment string.
   */
  private $commentcb;

  /**
   * Construct a new instance with the given reader and callbacks.
   *
   * @param Command $reader
   *   Instance of Command::ReadTable used to generate an array of plain UDC
   *   records.
   *
   * @param callable $propcallback
   *   A function with the signature `f($start, $end, $fields, $comment) -> array`
   *   returning key-value pairs for the given record or NULL.
   *
   * @param callable $commentcallback
   *   (Optional) A function with the signature `f($start, $end, $fields,
   *   $comment) -> string` returning the comment string which should be used
   *   for the new Uniprop instance.
   */
  public function __construct(Command $reader, $propcallback, $commentcallback = NULL) {
    parent::__construct($reader);
    $this->propcb = $propcallback;
    $this->commentcb = $commentcallback;
  }

  protected function getProps($start, $end, $fields, $comment) {
    return call_user_func($this->propcb, $start, $end, $fields, $comment);
  }

  protected function getComment($start, $end, $fields, $comment) {
    if ($this->commentcb) {
      return call_user_func($this->commentcb, $start, $end, $fields, $comment);
    }
  }
}
