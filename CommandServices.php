<?php
/**
 * @file
 * Definition of Znerol::Unidata::CommandServices.
 */

namespace Znerol\Unidata;

/**
 * Contains instances of objects frequently used from within implementations of
 * Command::run method.
 */
interface CommandServices
{
  /**
   * Return a Fetcher instance.
   */
  public function getFetcher();

  /**
   * Return a Uniprop::Set instance.
   */
  public function getSet();
}
