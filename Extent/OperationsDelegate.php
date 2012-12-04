<?php
/**
 * @file
 * Definition of Znerol::Unidata::Extent::OperationsDelegate
 */

namespace Znerol\Unidata\Extent;

/**
 * Interface for classes implementing unary or binary operations on extents.
 *
 * An extent can be anything capable of encoding a continous range on a
 * sequence of discrete values. See Extent::Base::Extent for a class
 * implementing an extent and Extent::Base::Operations for an appropriate
 * implementation of binary operations on that extent class.
 */
interface OperationsDelegate
{
  /**
   * Comparison callback for [usort](http://php.net/usort)
   *
   * Return negative value if first object starts at a lower offset. If first 
   * object starts at a higher offset return positive integer. If both extents 
   * start at the same position return negative int if first is shorter and 
   * positive int if second is shorter. Return 0 if both are equal.
   */
  public function compare($a, $b);

  /**
   * Return `true` if first is left of second extent
   *
   * Return `true` if `$a` and `$b` are not overlapping and `$a` starts at a 
   * lower offset. Otherwise return `false`.
   */
  public function left($a, $b);

  /**
   * Return `true` if given extents are disjoint
   *
   * Return `true` if `$a` and `$b` are not overlapping and there is a gap of 
   * at least 1 between them. Otherwise return `false`.
   */
  public function disjoint($a, $b);

  /**
   * Return `true` if `$a` and `$b` are overlapping
   */
  public function overlap($a, $b);

  /**
   * Join second extent to the first one
   *
   * Return an array of extents derived by joining the extent `$b` to `$a`. The 
   * extents may not be disjoint but may overlap. If joining of the given 
   * extents is not possible, this method must throw an InvalidArgumentException.
   */
  public function join($a, $b);

  /**
   * Divide first extent by the second one
   *
   * Return an array of extents derived by splitting `$a` on the `$divisor`. 
   * The extents must overlap. If splitting is not allowed, the method must 
   * throw an InvalidArgumentException.
   */
  public function split($a, $divisor);
}
