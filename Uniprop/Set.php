<?php
/**
 * @file
 * Definition of Znerol::Unidata::Uniprop::Set
 */

namespace Znerol\Unidata\Uniprop;

use Znerol\Unidata\Extent;

/**
 * This class implements set operations on array of Uniprop instances.
 * To the functions provided by Extent::Set this class adds a method for
 * grouping Uniprop instances according to their property values.
 */
class Set extends Extent\Set
{
  public function __construct($ops = NULL) {
    parent::__construct($ops ?: new Operations());
  }

  /**
   * Regroup the given extents into a nested array with two levels. On the
   * first level entries are keyed by property names, on the second level by
   * property values. Sort order is preserved.
   *
   * @param array $extents
   *   List of extents.
   *
   * @param array $boolprops
   *   (optional) ByRef, for each detected property name sets
   *   `$boolprops[$propname]` to `true` if the property values are booleans.
   *   Otherwise the entry is set to `false`.
   *
   * @retval array
   *   Nested array of extents indexed by property name and property values.
   */
  public function group($extents, &$boolprops = array()) {
    $result = array();

    foreach ($extents as $extent) {
      $props = $extent->getProperties();

      foreach ($props as $key => $value) {
        // Ensure that boolean and non-boolean properties are not mixed
        if (isset($boolprops[$key])) {
          if (is_bool($value) != $boolprops[$key]) {
            throw new \Exception('Boolean property values may not be mixed with non-boolean property values');
          }
        }
        else {
          $boolprops[$key] = is_bool($value);
        }

        // Skip over (boolean) false values
        if ($boolprops[$key] && !$value) {
          continue;
        }

        $result[$key][$value][] = $extent;
      }
    }

    return $result;
  }
}
