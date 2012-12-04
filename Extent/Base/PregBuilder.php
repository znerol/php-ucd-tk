<?php
/**
 * Definition of Znerol::Unidata::Extent::Base::PregBuilder
 */

namespace Znerol\Unidata\Extent\Base;

/**
 * Builder class capable of generating PCRE compatible [character classes] and
 * patterns suitable for the PHP [preg_match_all] function.
 *
 * [character classes]:
 *   http://php.net/manual/en/regexp.reference.character-classes.php
 * [preg_match_all]:
 *   http://php.net/preg_match_all
 */
class PregBuilder {
  private $subquantifier = '+';
  private $delimiter = '#';
  private $flags = 'uS';

  /**
   * Given an array of extents return a preg character class containing all 
   * codepoints.
   *
   * @param array $extents
   *   List of Extent::Base::Extent instances.
   *
   * @param string
   *   PCRE compatible character class without enclosing brackets.
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

  /**
   * Given a string containing a character class definition, return a
   * [subpattern].
   *
   * @param string $charclass
   *   PCRE compatible character class without enclosing brackets.
   *
   * @param string $name
   *   (optional) name of the subpattern.
   *
   * @param bool $negate
   *   (optional) negate the character class by prepending a circumflex (^) if
   *   set to `true`.
   *
   * [subpattern]:
   *   http://php.net/manual/en/regexp.reference.subpatterns.php
   */
  public function subpattern($charclass, $name = NULL, $negate = false) {
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

  /**
   * Given a list of subpatterns generate a PCRE compatible pattern using
   * [alternation].
   *
   * @param array $subpatterns
   *   List of subpatterns
   *
   * @retval string
   *   PCRE compatible pattern suitable for [preg_match_all].
   *
   * [alternations]:
   *   http://php.net/manual/en/regexp.reference.alternation.php
   * [preg_match_all]:
   *   http://php.net/preg_match_all
   */
  public function pattern($subpatterns) {
    return $this->delimiter .
      implode('|', $subpatterns) .
      $this->delimiter .
      $this->flags;
  }
}
