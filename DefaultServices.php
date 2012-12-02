<?php

namespace Znerol\Unidata;

class DefaultServices implements CommandServices, RunnerService {
  private $runner;
  private $set;
  private $urlOpener;

  public function __construct(Runner $runner = NULL) {
    $this->runner = $runner ?: new Runner\Base();
    $this->set = new Uniprop\Set();
    $this->urlOpener = $this->runner;
  }

  /**
   * Return a Znerol\Unidata\Runner instance.
   */
  public function getRunner() {
    return $this->runner;
  }

  /**
   * Return a Znerol\Unidata\RunnerService instance.
   */
  public function getRunnerService() {
    return $this;
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

  /**
   * Run a command with the appropriate runner and runner services instance.
   */
  public function run(Command $cmd) {
    return $this->getRunner()->run($cmd, $this);
  }
}
