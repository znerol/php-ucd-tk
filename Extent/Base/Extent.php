<?php

namespace Znerol\Unidata\Extent\Base;

class Extent 
{
  private $head;
  private $next;

  public function __construct($head, $next) {
    $this->head = $head;
    $this->next = $next;
  }

  public function getHead() {
    return $this->head;
  }

  public function getNext() {
    return $this->next;
  }
}
