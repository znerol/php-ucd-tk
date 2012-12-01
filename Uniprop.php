<?php

namespace Znerol\Unidata;

class Uniprop extends Extent\Base\Extent
{
  /**
   * Key-Properties pairs of properties attached to this extent.
   */
  private $properties;

  /**
   * Arbitrary string.
   */
  private $comment;

  public function __construct($head, $next, $properties = array(), $comment = NULL) {
    parent::__construct($head, $next);
    $this->properties = $properties;
    $this->comment = $comment;
  }

  public function getProperties() {
    return $this->properties;
  }

  public function getComment() {
    return $this->comment;
  }
}
