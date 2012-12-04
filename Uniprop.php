<?php
/**
 * @file
 * Definition of Znerol::Unidata::Uniprop
 */

namespace Znerol\Unidata;

/**
 * Representation of an unicode table data record.
 */
class Uniprop extends Extent\Base\Extent
{
  /**
   * Key-Value pairs of properties attached to this extent.
   */
  private $properties;

  /**
   * Arbitrary string.
   */
  private $comment;

  /**
   * Construct a new record.
   *
   * @param int $head
   *   First codepoint in range
   *
   * @param int $next
   *   First codepoint after the range
   *
   * @param array $properties
   *   List of key-value pairs of unicode properties attached to this range
   *
   * @param string $comment
   *   An arbitrary string
   */
  public function __construct($head, $next, $properties = array(), $comment = NULL) {
    parent::__construct($head, $next);
    $this->properties = $properties;
    $this->comment = $comment;
  }

  /**
   * Return the key-value pairs of unicode properties.
   *
   * @retval array
   *   Unicode properties attached to this range.
   */
  public function getProperties() {
    return $this->properties;
  }

  /**
   * Return the comment
   *
   * @retval string
   *   Comment string associated with this range.
   */
  public function getComment() {
    return $this->comment;
  }
}
