<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;

class UnipropAll extends UnipropBase
{
  private $propname;
  private $comment;

  public function __construct(Command $reader, $propname, $comment = NULL) {
    parent::__construct($reader);
    $this->propname = $propname;
    $this->comment = $comment;
  }

  protected function getProps($start, $end, $fields, $comment) {
    return array($this->propname => $fields[1]);
  }

  protected function getComment($start, $end, $fields, $comment) {
    return is_string($this->comment) ? $this->comment :
      parent::getComment($start, $end, $fields, $comment);
  }
}
