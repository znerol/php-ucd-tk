<?php

namespace Znerol\Unidata\Extent\Base;

class PregBuilder {
  private $subquantifier = '+';
  private $delimiter = '#';
  private $flags = 'uS';

  /**
   * Given an array of extents return a preg character class containing all 
   * codepoints.
   */
  public function charclass($extents) {
    $charclass = '';

    foreach ($extents as $extent) {
      $start = $extent->getHead();
      $end = $extent->getNext() - 1;

      $charclass .= $start == $end ?
        sprintf('\\x{%X}', $start) : sprintf('\\x{%X}-\\x{%X}', $start, $end);
    }

    return $charclass;
  }

  public function subpattern($charclass, $name = NULL, $negate = FALSE) {
    $subpattern = '(';

    if ($name) {
      $subpattern .= '?P<' . $name . '>';
    }

    $subpattern .= '[';

    if ($negate) {
      $subpattern .= '^';
    }

    $subpattern .= $charclass;
    $subpattern .= ']';
    $subpattern .= $this->subquantifier;
    $subpattern .= ')';

    return $subpattern;
  }

  public function pattern($subpatterns) {
    return $this->delimiter .
      implode('|', $subpatterns) .
      $this->delimiter .
      $this->flags;
  }
}
