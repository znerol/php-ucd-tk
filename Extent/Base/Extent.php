<?php
/**
 * Definition of Znerol::Unidata::Extent::Base::Extent
 */

namespace Znerol\Unidata\Extent\Base;

/**
 * Basic implementation of an extent.
 */
class Extent 
{
  /**
   * First position in this range.
   */
  private $head;

  /**
   * First position after this range.
   */
  private $next;

  /**
   * Construct a new extent.
   *
   * @param int $head
   *   First position in this range.
   *
   * @param int $next
   *   First position after the range.
   */
  public function __construct($head, $next) {
    $this->head = $head;
    $this->next = $next;
  }

  /**
   * Return first position of the range.
   */
  public function getHead() {
    return $this->head;
  }

  /**
   * Return first position after the range.
   */
  public function getNext() {
    return $this->next;
  }
}
