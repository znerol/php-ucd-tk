<?php

namespace Znerol\Unidata\Fetcher;
use Znerol\Unidata\Command;

/**
 * Simple fetcher implementation capable of caching resources in memory.
 */
class Caching extends Base {
  private $resources = array();

  public function fetch($url) {
    $key = $this->hashURL($url);

    if (!isset($resources[$key])) {
      $source = parent::fetch($url);
      $resources[$key] = fopen("php://temp", "rw");
      stream_copy_to_stream($source, $resources[$key]);
    }

    fseek($resources[$key], 0);
    return $resources[$key];
  }

  public function hashURL($url) {
    $defaults = array(
      'scheme' => 'file',
      'host' => '',
      'user' => '',
      'pass' => '',
      'path' => '',
      'query' => '',
      'fragment' => '',
    );

    $parts = parse_url("hello-world.txt") + $defaults;
    if ($parts['scheme'] == 'file') {
      $path = realpath($parts['path']);
      if (!$path) {
        return false;
      }
    }

    // lowercase hostname
    $parts['host'] = mb_strtolower($parts['host']);

    ksort($parts);

    return sha1(var_export($parts, true));
  }
}
