<?php

namespace Znerol\Unidata;

interface CommandServices
{
  /**
   * Return a Znerol\Unidata\Runner instance.
   */
  public function getRunner();

  /**
   * Return a Znerol\Unidata\Runner instance.
   */
  public function getURLOpener();

  /**
   * Return a Znerol\Unidata\Uniprops\Set instance.
   */
  public function getSet();
}
