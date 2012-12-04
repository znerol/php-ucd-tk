<?php
/**
 * @file
 * Definition of Znerol::Unidata::Extent::Set
 */

namespace Znerol\Unidata\Extent;

/**
 * This class implements set operations on array of extents like union and 
 * difference.
 */
class Set {
  /**
   * Reference to an Extent::OperationsDelegate
   */
  private $ops;

  /**
   * Construct new instance providing set operations for extents.
   *
   * @param Extent::OperationsDelegate $operationsDelegate
   *   A class capable of performing binary operations on extents.
   */
  public function __construct(OperationsDelegate $operationsDelegate) {
    $this->ops = $operationsDelegate;
  }

  /**
   * Build the union of two lists of extents.
   *
   * Note that the objects in this list need to be of a type matching the
   * $operationsDelegate implementation.
   *
   * @param array $a
   *   List of extents.
   *
   * @param array $b
   *   (Optional) additional list of extents.
   *
   * @retval array
   *   List of non-overlapping extents from `$a` and `$b`.
   */
  public function union($a, $b = NULL) {
    $extents = empty($b) ? $a : array_merge($a, $b);

    if (empty($extents)) {
      return $a;
    }

    $extents = $this->sorted($extents);

    $union = array(array_shift($extents));
    foreach ($extents as $extent) {
      // Merge overlapping or adjacent extents
      if ($this->ops->disjoint(end($union), $extent)) {
        $union[] = $extent;
      }
      else {
        $new = $this->ops->join(array_pop($union), $extent);
        $union = array_merge($union, $new);
      }
    }

    return $union;
  }

  /**
   * Returns the list of extents from the first parameter with any overlaps 
   * with the extents from second parameter removed.
   *
   * @param array $a
   *   List of extents.
   *
   * @param array $b
   *   (Optional) additional list of extents.
   *
   * @retval array
   *   List of non-overlapping extents from `$a` minus `$b`.
   */
  public function difference($a, $b) {
    if (empty($a) || empty($b)) {
      return $a;
    }

    $diff = array();
    $pushdiff = function($extent) use (&$diff) {
      $diff[] = $extent;
    };

    $subject = reset($a);
    $divisor = reset($b);
    while($subject !== false && $divisor !== false) {
      // Fast forward as long as the subject is left of the divisor
      if (!$this->spool($subject, $a, $divisor, $pushdiff)) {
        break;
      }

      // Fast forward as long as the divisor is left of the subject
      if (!$this->spool($divisor, $b, $subject)) {
        break;
      }

      if (!$this->ops->overlap($subject, $divisor)) {
        continue;
      }

      // Divide the subject by the divisor. This operation results in an array
      // with zero, one or two new extents.
      $pair = $this->ops->split($subject, $divisor);

      // If the extent was eaten up completely, we need a new one. Otherwise we
      // continue with the right hand part.
      $subject = empty($pair) ? next($a) : array_pop($pair);

      // Also we push the left hand part to the result if there is any.
      array_walk($pair, $pushdiff);
    }

    // Finally push remaining extents into result
    $this->finalize($subject, $a, $pushdiff);

    return $diff;
  }

  /**
   * Given a sorted array of extents, return the index of the first extent 
   * disjoint to its predecessor. Returns false if there are no gaps.
   *
   * @param array $extents
   *   List of extents.
   *
   * @retval int
   *   Index of first extent disjoint to its precedessor or false.
   */
  public function firstGap($extents) {
    $last = reset($extents);
    while(($extent = next($extents)) !== false) {
      if ($this->ops->disjoint($last, $extent)) {
        return key($extents);
      }
    }

    return false;
  }

  /**
   * Given a sorted array of extents, return the index of the first extent
   * overlapping with its predecessor. Returns false if there are no overlaps.
   *
   * @param array $extents
   *   List of extents.
   *
   * @retval int
   *   Index of first extent overlapping with its precedessor or false.
   */
  public function firstOverlap($extents) {
    $last = reset($extents);
    while(($extent = next($extents)) !== false) {
      if ($this->ops->overlap($last, $extent)) {
        return key($extents);
      }
    }

    return false;
  }

  /**
   * Return an array containing the given extents in ascending order
   *
   * @param array $extents
   *   List of extents.
   *
   * @retval array
   *   Sorted list of extents.
   */
  public function sorted($extents) {
    usort($extents, array($this->ops, 'compare'));
    return $extents;
  }

  /**
   * Walk through extent list as long as the end of the extent is smaller than 
   * the given limit. Return last extent or false, if the end of the list was 
   * reached.
   */
  private function spool(&$extent, &$list, $limit, $callback = NULL) {
    while($extent !== false && $this->ops->left($extent, $limit)) {
      if ($callback) {
        call_user_func($callback, $extent);
      }
      $extent = next($list);
    }

    return $extent !== false;
  }

  /**
   * Apply the callback function on each remaining item until the end of the 
   * list is reached.
   */
  private function finalize(&$extent, &$list, $callback) {
    while($extent !== false) {
      call_user_func($callback, $extent);
      $extent = next($list);
    }
  }
}
