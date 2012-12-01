<?php

namespace Znerol\Unidata\Uniprop;

use InvalidArgumentException;
use Znerol\Unidata\Extent\Base;
use Znerol\Unidata\Uniprop;

class Operations extends Base\Operations
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
