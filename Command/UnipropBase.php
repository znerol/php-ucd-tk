<?php
namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

abstract class UnipropBase implements Command
{
  private $set;

  public function __construct(Uniprop\Set $set) {
    $this->set = $set;
  }

  public function run(Runner $runner) {
    $rows = $runner->run($this->reader);

    $extents = array();
    foreach ($rows as $row) {
      list($start, $end, $fields, $comment) = $row;
      $props = $this->getProps($start, $end, $fields, $comment);
      if (!empty($props)) {
        $comment = $this->getComment($start, $end, $fields, $comment);
        $extents[] = new Uniprop($start, $end + 1, $props, $comment);
      }
    }

    return $this->set->union($extents);
  }

  /**
   * Extract and return properties array from a unicode table row.
   */
  protected abstract function getProps($start, $end, $fields, $comment);

  /**
   * Extract and return a comment.
   */
  protected function getComment($start, $end, $fields, $comment) {
    return $comment;
  }
}
