<?php

use Znerol\Unidata\Uniprop;
use Znerol\Unidata\Fetcher;

class CachingServices implements Znerol\Unidata\CommandServices {
  private $set;
  private $fetcher;

  public function __construct() {
    $this->set = new Uniprop\Set();
    $this->fetcher = new Fetcher\Caching();
  }

  /**
   * Return a Znerol\Unidata\Fetcher instance.
   */
  public function getFetcher() {
    return $this->fetcher;
  }

  /**
   * Return a Znerol\Unidata\Uniprops\Set instance.
   */
  public function getSet() {
    return $this->set;
  }
}
