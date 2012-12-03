<?php

use Znerol\Unidata\CommandServices;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Runner;

class RunnerCachingTestCallbackCommand implements Znerol\Unidata\Command
{
  private $callback;

  public function __construct($callback) {
    $this->callback = $callback;
  }

  public function run(Runner $runner, CommandServices $srv) {
    return call_user_func($this->callback, $runner, $srv);
  }
}

class RunnerCachingTest extends PHPUnit_Framework_TestCase
{
  private $extents;

  public function setUp() {
    $this->runner = new Runner\Caching(new DefaultServices());
  }

  public function testShouldCacheResult() {
    $counter = 0;

    $command = new RunnerCachingTestCallbackCommand(function($runner, $srv) use (&$counter) {
      return ($counter += 1);
    });

    // The command should be evaluated the first time
    $result = $this->runner->run($command);
    $this->assertEquals(1, $result);
    $this->assertEquals(1, $counter);

    // The second time only the cached result should be returned
    $result = $this->runner->run($command);
    $this->assertEquals(1, $result);
    $this->assertEquals(1, $counter);
  }
}
