<?php

namespace Znerol\Unidata;

interface CommandServices
{
  /**
   * Return a Znerol\Unidata\Fetcher instance.
   */
  public function getFetcher();

  /**
   * Return a Znerol\Unidata\Uniprops\Set instance.
   */
  public function getSet();
}
