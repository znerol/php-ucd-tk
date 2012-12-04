<?php
/**
 * @file
 * Definition of Znerol::Unidata::Fetcher::Caching
 */

namespace Znerol\Unidata\Fetcher;
use Znerol\Unidata\Command;

/**
 * Simple Znerol::Unidata::Fetcher implementation capable of caching resources 
 * in memory.
 */
class Caching extends Base {
  private $resources = array();

  /**
   * @copydoc Znerol::Unidata::Fetcher::fetch
   */
  public function fetch($url) {
    $key = $this->hashURL($url);

    if (!isset($this->resources[$key])) {
      $source = parent::fetch($url);
      $this->resources[$key] = fopen("php://temp", "rw");
      stream_copy_to_stream($source, $this->resources[$key]);
    }

    fseek($this->resources[$key], 0);
    return $this->resources[$key];
  }

  /**
   * Calculate a unique key after normalizing the given URL.
   *
   * @param string $url
   *   An URL
   * @retval string
   *   A unique key for that URL
   */
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

    $parts = parse_url($url) + $defaults;
    if ($parts['scheme'] == 'file') {
      $path = realpath($parts['path']);
      if (!$path) {
        return false;
      }
      $parts['path'] = $path;
    }

    // lowercase hostname
    $parts['host'] = mb_strtolower($parts['host']);

    ksort($parts);

    return sha1(var_export($parts, true));
  }
}
