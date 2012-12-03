<?php

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class CommandAggregateTest extends PHPUnit_Framework_TestCase
{
  private $runner;

  public function setUp() {
    $this->runner = new Runner\Base(new DefaultServices());

    // gc=White_Space
    $whitespace = array(
      new Uniprop(0x0009, 0x000E, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x0020, 0x0021, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x0085, 0x0086, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x00A0, 0x00A1, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x1680, 0x1681, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x180E, 0x180F, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x2000, 0x200B, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x2028, 0x2029, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x2029, 0x202A, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x202F, 0x2030, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x205F, 0x2060, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x3000, 0x3001, array('TESTPROP' => 'White_Space')),
    );
    $this->build_whitespace = $this->getMock('Znerol\Unidata\Command');
    $this->build_whitespace->expects($this->once())
      ->method('run')
      ->will($this->returnValue($whitespace));

    // Line_Break=BK
    $mandatory_break = array(
      new Uniprop(0x000B, 0x000D, array('TESTPROP' => 'BK')),
      new Uniprop(0x2028, 0x2029, array('TESTPROP' => 'BK')),
      new Uniprop(0x2029, 0x202A, array('TESTPROP' => 'BK')),
    );
    $this->build_mandatory_break = $this->getMock('Znerol\Unidata\Command');
    $this->build_mandatory_break->expects($this->once())
      ->method('run')
      ->will($this->returnValue($mandatory_break));
  }

  public function testAggregateBKFirst() {
    $expected = array(
      // Next-property split by adjacent BK-extent from 0x000B -> 0x000D
      new Uniprop(0x0009, 0x000B, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x000B, 0x000D, array('TESTPROP' => 'BK')),
      new Uniprop(0x000D, 0x000E, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x0020, 0x0021, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x0085, 0x0086, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x00A0, 0x00A1, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x1680, 0x1681, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x180E, 0x180F, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x2000, 0x200B, array('TESTPROP' => 'White_Space')),
      // BK wins. Also Adjacent extents with identical property-values are 
      // merged.
      new Uniprop(0x2028, 0x202A, array('TESTPROP' => 'BK')),
      new Uniprop(0x202F, 0x2030, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x205F, 0x2060, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x3000, 0x3001, array('TESTPROP' => 'White_Space')),
    );

    $command = new Command\Aggregate(array(
      $this->build_mandatory_break,
      $this->build_whitespace,
    ));

    $result = $this->runner->run($command);
    $this->assertEquals($expected, $result);
  }

  public function testAggregateWSFirst() {
    $expected = array(
      new Uniprop(0x0009, 0x000E, array('TESTPROP' => 'White_Space')),
      // BK is completely hidden by WS.
      new Uniprop(0x0020, 0x0021, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x0085, 0x0086, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x00A0, 0x00A1, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x1680, 0x1681, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x180E, 0x180F, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x2000, 0x200B, array('TESTPROP' => 'White_Space')),
      // Adjacent extents with identical property-values are merged.
      new Uniprop(0x2028, 0x202A, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x202F, 0x2030, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x205F, 0x2060, array('TESTPROP' => 'White_Space')),
      new Uniprop(0x3000, 0x3001, array('TESTPROP' => 'White_Space')),
    );

    $command = new Command\Aggregate(array(
      $this->build_whitespace,
      $this->build_mandatory_break,
    ));

    $result = $this->runner->run($command);
    $this->assertEquals($expected, $result);
  }

}
