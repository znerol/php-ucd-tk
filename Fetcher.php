<?php
/**
 * @file
 * Definition of Znerol::Unidata::Fetcher.
 */

namespace Znerol\Unidata;

/**
 * Interface for classes implementing retrival of documents using URLs.
 */
interface Fetcher
{
  /**
   * Return contents of the given URL.
   *
   * @param string $url
   *   A stream URL with a scheme supported by your PHP installation.
   *
   * @see http://php.net/manual/en/wrappers.php
   */
  public function fetch($url);
}
