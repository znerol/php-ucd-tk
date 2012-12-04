<?php
/**
 * @file
 * Definition of Znerol::Unidata::Uniprop::Operations
 */

namespace Znerol\Unidata\Uniprop;

use InvalidArgumentException;
use Znerol\Unidata\Uniprop;

/**
 * Implementation for unary and binary operations on instances of
 * Uniprop.
 */
class Operations extends \Znerol\Unidata\Extent\Base\Operations
{
  protected function newExtent($head, $next, $context = array()) {
    return new Uniprop($head, $next, $context['args'][0]->getProperties(), $context['args'][0]->getComment());
  }

  public function join($a, $b) {
    if ($a->getProperties() == $b->getProperties()) {
      return parent::join($a, $b);
    }

    if (!$this->overlap($a, $b)) {
      // Just return the params if a does not overlap b
      return array($a, $b);
    }

    throw new InvalidArgumentException('Failed to join overlapping property extents');
  }
}
