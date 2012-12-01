<?php

namespace Znerol\Unidata\Extent;

interface OperationsDelegate
{
  public function compare($a, $b);

  public function left($a, $b);

  public function disjoint($a, $b);

  public function overlap($a, $b);

  public function join($a, $b);

  public function split($a, $divisor);

  public function range($extent);
}
