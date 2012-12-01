<?php

namespace Znerol\Unidata;

interface DumperServices
{
  /**
   * Return a Znerol\Unidata\Extent\Base\PregBuilder instance.
   */
  public function getPregBuilder();
}
