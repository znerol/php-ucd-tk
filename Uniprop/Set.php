<?php

namespace Znerol\Unidata\Uniprop;

use Znerol\Unidata\Extent;

class Set extends Extent\Set
{
  public function __construct($ops = NULL) {
    parent::__construct($ops ?: new Operations());
  }

  /**
   * Regroup the given extents into a nested array with two levels. On the
   * first level entries are keyed by property names, on the second level by
   * property values. Sort order is preserved.
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
