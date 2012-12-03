<?php

use Znerol\Unidata\Fetcher;

class FetcherCachingTest extends PHPUnit_Framework_TestCase
{
  private $extents;

  public function setUp() {
    $this->fetcher = new Fetcher\Caching();
    $this->tmpfile = tempnam(sys_get_temp_dir(), 'phpunit');
  }

  public function tearDown() {
    unlink($this->tmpfile);
  }

  public function testShouldCacheResult() {
    $first_chunk = 'hello world';
    $second_chunk = 'and now for something completely different';

    file_put_contents($this->tmpfile, $first_chunk);
    sleep(1);
    $stream = $this->fetcher->fetch($this->tmpfile);
    $this->assertEquals($first_chunk, fread($stream, 64));

    file_put_contents($this->tmpfile, $second_chunk);
    $stream = $this->fetcher->fetch($this->tmpfile);
    // Must still return the first string, not the second
    $this->assertEquals($first_chunk, fread($stream, 64));
  }
}
