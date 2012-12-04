<?php
/**
 * @file
 * Definition of Znerol::Unidata::Extent::Base::Operations
 */

namespace Znerol\Unidata\Extent\Base;

use Znerol\Unidata\Extent\OperationsDelegate;

/**
 * Implementation for unary and binary operations on instances of
 * Extent::Basic::Extent.
 */
class Operations implements OperationsDelegate
{
  /**
   * Construct and return a new extent.
   *
   * @param int $head
   *   First position in this range.
   *
   * @param int $next
   *   First position after the range.
   *
   * @param array $context
   *   Key-value pairs defining the method and conditions which lead to the
   *   necessity to construct a new extent.
   */
  protected function newExtent($head, $next, $context = array()) {
    return new Extent($head, $next);
  }

  public function compare($a, $b) {
    return $a->getHead() - $b->getHead() ?: $a->getNext() - $b->getNext();
  }

  public function left($a, $b) {
    return $a->getNext() <= $b->getHead();
  }

  public function disjoint($a, $b) {
    return $a->getHead() > $b->getNext() || $a->getNext() < $b->getHead();
  }

  public function overlap($a, $b) {
    return $a->getNext() > $b->getHead() && $a->getHead() < $b->getNext();
  }

  public function join($a, $b) {
    $context = array('func' => __FUNCTION__, 'args' => func_get_args());
    $result = array();

    $head = min($a->getHead(), $b->getHead());
    $next = max($a->getNext(), $b->getNext());

    $result[] = $this->newExtent($head, $next, $context);

    return $result;
  }

  public function split($a, $divisor) {
    $context = array('func' => __FUNCTION__, 'args' => func_get_args());
    $result = array();

    if ($a->getHead() < $divisor->getHead()) {
      $result[] = $this->newExtent($a->getHead(), $divisor->getHead(), $context);
    }
    if ($divisor->getNext() < $a->getNext()) {
      $result[] = $this->newExtent($divisor->getNext(), $a->getNext(), $context);
    }

    return $result;
  }
}
