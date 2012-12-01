<?php
namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

abstract class UnipropBase implements Command
{
  private $reader;

  private $set;

  public function __construct(Command $reader, Uniprop\Set $set) {
    $this->reader = $reader;
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
        $next = ($end ?: $start) + 1;
        $extents[] = new Uniprop($start, $next, $props, $comment);
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
