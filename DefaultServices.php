<?php
/**
 * @file
 * Definition of Znerol::Unidata::DefaultServices
 */

namespace Znerol\Unidata;

/**
 * Default implementation of CommandServices.
 */
class DefaultServices implements CommandServices {
  private $set;
  private $fetcher;

  public function __construct() {
    $this->set = new Uniprop\Set();
    $this->fetcher = new Fetcher\Base();
  }

  /**
   * @copydoc CommandServices::getFetcher
   */
  public function getFetcher() {
    return $this->fetcher;
  }

  /**
   * @copydoc CommandServices::getSet
   */
  public function getSet() {
    return $this->set;
  }

  /**
   * Replace the default fetcher with the given instance.
   *
   * @param Fetcher $fetcher
   *   New Fetcher instance
   */
  public function setFetcher(Fetcher $fetcher) {
    $this->fetcher = $fetcher;
  }

  /**
   * Replace the default uniprop set instance with the given object.
   *
   * @param Uniprop::Set $set
   *   New Uniprop::Set instance
   */
  public function setSet(Uniprop\Set $set) {
    $this->set = $set;
  }
}
