<?php

namespace Znerol\Unidata;

class DefaultServices implements CommandServices {
  private $set;
  private $fetcher;

  public function __construct() {
    $this->set = new Uniprop\Set();
    $this->fetcher = new Fetcher\Base();
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
