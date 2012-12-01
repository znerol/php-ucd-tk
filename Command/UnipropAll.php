<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class UnipropAll extends UnipropBase
{
  private $propname;

  public function __construct(Uniprop\Set $set, $propname, $comment = NULL) {
    parent::__construct($set);
    $this->propname = $propname;
    $this->comment = $comment;
  }

  protected function getProps($start, $end, $fields, $comment) {
    return array($this->propname => $fields[1]);
  }

  protected function getComment($start, $end, $fields, $comment) {
    return is_string($this->comment) ?: $comment;
  }
}
