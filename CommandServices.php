<?php
/**
 * @file
 * Definition of Znerol::Unidata::CommandServices.
 */

namespace Znerol\Unidata;

/**
 * Contains instances of objects frequently used from within implementations of
 * Znerol::Unidata::Command::run method.
 */
interface CommandServices
{
  /**
   * Return a Znerol::Unidata::Fetcher instance.
   */
  public function getFetcher();

  /**
   * Return a Znerol::Unidata::Uniprop::Set instance.
   */
  public function getSet();
}
