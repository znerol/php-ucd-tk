<?php

namespace Znerol\Unidata;

interface RunnerService {
  public function run(Command $cmd);
}
