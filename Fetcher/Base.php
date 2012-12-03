<?php

namespace Znerol\Unidata\Fetcher;

use Znerol\Unidata\Fetcher;

class Base implements Fetcher
{
  public function fetch($url) {
    return fopen($url, 'r');
  }
}
