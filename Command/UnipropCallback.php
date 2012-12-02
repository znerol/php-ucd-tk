<?php

namespace Znerol\Unidata\Command;

use Znerol\Unidata\Command;

class UnipropCallback extends UnipropBase
{
  private $propcb;
  private $commentcb;

  public function __construct(Command $reader, $propcallback, $commentcallback = NULL) {
    parent::__construct($reader);
    $this->propcb = $propcallback;
    $this->commentcb = $commentcallback;
  }

  protected function getProps($start, $end, $fields, $comment) {
    return call_user_func($this->propcb, $start, $end, $fields, $comment);
  }

  protected function getComment($start, $end, $fields, $comment) {
    if ($this->commentcb) {
      return call_user_func($this->commentcb, $start, $end, $fields, $comment);
    }
  }
}
