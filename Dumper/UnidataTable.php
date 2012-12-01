<?php

namespace Znerol\Unidata\Dumper;

/**
 * Write property extents to a stream in UCD format.
 */
class UnidataTable implements \Znerol\Unidata\Dumper
{
  public function dump($stream, $extents) {
    // Group extents by properties and values
    $bypropval = array();
    $boolprops = array();

    foreach ($extents as $extent) {
      $props = $extent->getProperties();

      foreach ($props as $key => $value) {
        if (is_bool($value)) {
          $boolprops[$key] = $key;
          if (!$value) {
            continue;
          }
        }

        $bypropval[$key][$value][] = $extent;
      }
    }

    fwrite($stream, $this->fileHeadComment());

    foreach ($bypropval as $prop => $values) {
      fwrite($stream, $this->propBeforeNameComment($prop, isset($boolprops[$key])));

      foreach ($values as $value => $extents) {
        fwrite($stream, $this->propBeforeValueComment($prop, $value, isset($boolprops[$key]), $extents));

        foreach ($extents as $extent) {
          $start = $extent->getHead();
          $end = $extent->getNext() - 1;

          if ($start == $end) {
            $line = $this->codepointToString($start, $prop, $value, isset($boolprops[$key]), $extent);
          }
          else {
            $line = $this->rangeToString($start, $end, $prop, $value, isset($boolprops[$key]), $extent);
          }
          fwrite($stream, $line);
        }

        fwrite($stream, $this->propAfterComment($prop, $value, isset($boolprops[$key]), $extents));
      }
    }
  }

  /**
   * Convert one codepoint to a string in the UCD format
   */
  protected function codepointToString($cp, $prop, $value, $isbool, $extent) {
    $propval = $isbool ? $prop : $value;
    $comment = $extent->getComment();
    return sprintf("%04.X       ; %s%s\n", $cp, $propval, $comment ? ' # ' . $comment : '');
  }

  /**
   * Convert a codepoint range to a string in the UCD format
   */
  protected function rangeToString($start, $end, $prop, $value, $isbool, $extent) {
    $propval = $isbool ? $prop : $value;
    $comment = $extent->getComment();
    return sprintf("%04.X..%04.X ; %s%s\n", $start, $end, $propval, $comment ? ' # ' . $comment : '');
  }

  /**
   * Return a comment block shown before the given group.
   */
  protected function propBeforeNameComment($prop, $isbool) {
    $text = "\n# ================================================\n";
    $text .= sprintf("\n# Property: %s\n\n", $prop);

    return $text;
  }

  /**
   * Return a comment block shown before the given group.
   */
  protected function propBeforeValueComment($prop, $value, $isbool, $extents) {
    $text = "\n# ================================================\n";
    $text .= sprintf("\n# %s=%s\n\n", $prop, $value);

    return $text;
  }

  /**
   * Return a comment block shown after the given group.
   */
  protected function propAfterComment($prop, $value, $isbool, $extents) {
    $total = array_sum(array_map(function($ex) {
      return $ex->getNext() - $ex->getHead();
    }, $extents));

    return sprintf("\n# Total code points: %d\n", $total);
  }

  /**
   * Return a comment block shown on top of the file
   */
  protected function fileHeadComment() {
    $text = sprintf("# This is a generated file, do not change'\n");
    $text .= strftime("# Date: %Y-%m-%d, %H:%M:%S %z\n");

    return $text;
  }
}
