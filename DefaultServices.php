<?php
/**
 * @file
 * Definition of Znerol::Unidata::DefaultServices
 */

namespace Znerol\Unidata;

/**
 * Default implementation of Znerol::Unidata::CommandServices.
 */
class DefaultServices implements CommandServices {
  private $set;
  private $fetcher;

  public function __construct() {
    $this->set = new Uniprop\Set();
    $this->fetcher = new Fetcher\Base();
  }

  /**
   * @copydoc Znerol::Unidata::CommandServices::getFetcher
   */
  public function getFetcher() {
    return $this->fetcher;
  }

  /**
   * @copydoc Znerol::Unidata::CommandServices::getSet
   */
  public function getSet() {
    return $this->set;
  }

  /**
   * Replace the default fetcher with the given instance.
   *
   * @param $fetcher
   *   New Znerol::Unidata::Fetcher instance
   */
  public function setFetcher(Fetcher $fetcher) {
    $this->fetcher = $fetcher;
  }

  /**
   * Replace the default uniprop set instance with the given object.
   *
   * @param $set
   *   New Znerol::Unidata::Uniprop\Set instance
   */
  public function setSet(Uniprop\Set $set) {
    $this->set = $set;
  }
}
