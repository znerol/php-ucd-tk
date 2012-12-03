<?php
/**
 * @file
 * Definition of Znerol\Unidata\Fetcher
 */

namespace Znerol\Unidata;

/**
 * Interface for classes implementing retrival of documents using URLs.
 */
interface Fetcher
{
  /**
   * Return contents of the given URL.
   */
  public function fetch($url);
}
