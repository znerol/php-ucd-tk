<?php

namespace Znerol\Unidata\Runner;
use Znerol\Unidata\Command;

/**
 * Simple runner implementation capable of caching the results of commands in 
 * memory.
 */
class Caching extends Base {
  private $resources = array();
  private $results = array();

  public function openURL($url) {
    $key = $this->hashURL($url);

    if (!isset($resources[$key])) {
      $source = parent::openURL($url);
      $resources[$key] = fopen("php://temp", "rw");
      stream_copy_to_stream($source, $resources[$key]);
    }
    
    fseek($resources[$key], 0);
    return $resources[$key];
  }

  public function run(Command $command) {
    $key = $this->hash($command);

    if (!isset($results[$key])) {
      $results[$key] = parent::run($command);
    }

    return $results[$key];
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
    ksort($parts);

    return $this->hash($parts);
  }

  public function hash($thing) {
    return sha1(var_export($thing, true));
  }
}
