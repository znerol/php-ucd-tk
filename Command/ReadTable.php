<?php

namespace Znerol\Unidata\Command;
use Znerol\Unidata\Command;
use Znerol\Unidata\CommandServices;

class ReadTable implements Command {
  private $url;

  public function __construct($url) {
    $this->url = $url;
  }

  public function run(CommandServices $srv) {
    $result = array();
    $expect_range_end = false;

    $stream = $srv->getURLOpener()->openURL($this->url);

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
