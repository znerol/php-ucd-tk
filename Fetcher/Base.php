<?php
/**
 * @file
 * Definition of Znerol::Unidata::Fetcher::Base.
 */

namespace Znerol\Unidata\Fetcher;

/**
 * Implementation of a very basic fetcher simply passing URLs to
 * [fopen](http://php.net/fopen).
 */
class Base implements \Znerol\Unidata\Fetcher
{
  /**
   * @copydoc Fetcher::fetch.
   */
  public function fetch($url) {
    return fopen($url, 'r');
  }
}
