<?php

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Runner;

class CommandReadTableTest extends PHPUnit_Framework_TestCase
{
  private $srv;

  public function setUp() {
    $this->srv = new DefaultServices(new Runner\Base());
  }

  public function testParseBlocks() {
    $command = new Command\ReadTable(dirname(__FILE__) . '/fixtures/Blocks.txt');

    $result = $this->srv->getRunner()->run($command, $this->srv);
    $this->assertEquals(220, count($result));
  }

  public function testParseUnicodeData() {
    $command = new Command\ReadTable(dirname(__FILE__) . '/fixtures/UnicodeDataExcerpt.txt');

    $expected = array(
      array(0x33FE, false, array('33FE', 'IDEOGRAPHIC TELEGRAPH SYMBOL FOR DAY THIRTY-ONE', 'So', '0', 'L', '<compat> 0033 0031 65E5', '', '', '', 'N', '', '', '', '', ''), ''),
      array(0x33FF, false, array('33FF', 'SQUARE GAL', 'So', '0', 'ON', '<square> 0067 0061 006C', '', '', '', 'N', '', '', '', '', ''), ''),
      array(0x3400, 0x4DB5, array('4DB5', '<CJK Ideograph Extension A, Last>', 'Lo', '0', 'L', '', '', '', '', 'N', '', '', '', '', ''), ''),
      array(0x4DC0, false, array('4DC0', 'HEXAGRAM FOR THE CREATIVE HEAVEN', 'So', '0', 'ON', '', '', '', '', 'N', '', '', '', '', ''), ''),
      array(0x4DC1, false, array('4DC1', 'HEXAGRAM FOR THE RECEPTIVE EARTH', 'So', '0', 'ON', '', '', '', '', 'N', '', '', '', '', ''), ''),
    );

    $result = $this->srv->getRunner()->run($command, $this->srv);
    $this->assertEquals($expected, $result);
  }
}
