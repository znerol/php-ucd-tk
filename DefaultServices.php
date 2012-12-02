<?php

namespace Znerol\Unidata;

class DefaultServices implements CommandServices {
  private $pregBuilder;
  private $runner;
  private $set;
  private $urlOpener;

  public function __construct($runner) {
    $this->pregBuilder = new Extent\Base\PregBuilder();
    $this->runner = $runner;
    $this->set = new Uniprop\Set();
    $this->urlOpener = $runner;
  }

  /**
   * Return a Znerol\Unidata\Runner instance.
   */
  public function getRunner() {
    return $this->runner;
  }

  /**
   * Return a Znerol\Unidata\Runner instance.
   */
  public function getURLOpener() {
    return $this->urlOpener;
  }

  /**
   * Return a Znerol\Unidata\Uniprops\Set instance.
   */
  public function getSet() {
    return $this->set;
  }
}
