<?php
/**
 * @file
 * Definition of Znerol::Unidata::Command::ReadTable.
 */

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;
use Znerol\Unidata\Runner;

/**
 * Implementation of a Command capable of parsing the contents of files
 * formatted according to the [UAX #44 File Format Conventions]. Most files
 * from the [UCD] are parsable using this class including [UnicodeData.txt] and
 * [PropList.txt].
 *
 * [UAX #44 File Format Conventions]:
 *   http://www.unicode.org/reports/tr44/#Format_Conventions
 * [UCD]:
 *   http://www.unicode.org/Public/UNIDATA/
 * [UnicodeData.txt]:
 *   http://www.unicode.org/Public/UNIDATA/UnicodeData.txt
 * [PropList.txt]:
 *   http://www.unicode.org/Public/UNIDATA/PropList.txt
 */
class ReadTable implements Command {
  private $url;

  /**
   * Construct a new table reader operating on the given URL.
   *
   * @param string $url
   *   URL or path to the desired UCD table file.
   */
  public function __construct($url) {
    $this->url = $url;
  }

  /**
   * Fetch and parse the unicode table. Return a list of records, each
   * comprising of an array of the form:
   *
   *     $rec = array($start, $end, $fields, $comment)
   *
   * Where `$start` is an `int` representing the first codepoint, `$end` is an
   * `int` representing the last codepoint or `false` if the record refers to a
   * single codepoint. `$fields` is an array of fields separated by a semicolon
   * in the underlying data. Finally `$comment` contains free text which
   * follows the hash (`#`) character until the end of line.
   *
   * @retval array
   *   List of records read from the UCD file.
   */
  public function run(Runner $runner, CommandServices $srv) {
    $result = array();
    $expect_range_end = false;

    $stream = $srv->getFetcher()->fetch($this->url);

    while (($line = fgets($stream)) !== false) {
      // Remove comments
      list($data, $comment) = explode('#', $line . '#', 2);
      // Remove leading and trailing whitespace (e.g. newline)
      $data = trim($data);
      if (empty($data)) {
        continue;
      }

      // Extract data fields
      $fields = array_map('trim', explode(';', $data));

      if ($expect_range_end) {
        // End of range like found in UnicodeData.txt
        if (!$fields[1] == $expect_range_end . 'Last>') {
          throw new Exception('Parser exception: expected range end.');
        }
        $last = hexdec($fields[0]);
        $expect_range_end = false;
      }
      elseif (substr_compare($fields[1], 'First>', -6) === 0) {
        // Encountered start of range like found in UnicodeData.txt
        $first = hexdec($fields[0]);
        $expect_range_end = substr($fields[1], 0, -6);
        continue;
      }
      else {
        // Parse codepoint
        $cps = explode('..', $fields[0]);

        // Extract first and last codepoint from range like found in PropList.txt
        $first = hexdec($cps[0]);
        $last = isset($cps[1]) ? hexdec($cps[1]) : false;
      }

      // Remove trailing # and space from comment
      $comment = rtrim(substr($comment, 0, -1));

      $result[] = array($first, $last, $fields, $comment);
    }

    return $result;
  }
}
