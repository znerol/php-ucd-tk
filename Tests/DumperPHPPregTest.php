<?php

use Znerol\Unidata\Command;
use Znerol\Unidata\Dumper;
use Znerol\Unidata\Extent\Base;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class DumperPHPPregTest extends PHPUnit_Framework_TestCase
{
  private $dumper;

  public function setUp() {
    $this->stream = fopen("php://memory", "rw");
    $this->dumper = new Dumper\PHPPreg('my\ns\TestClass',
      new Uniprop\Set(), new Base\PregBuilder());
  }

  public function testWriteSimple() {
    $extents = array(
      new Uniprop(0x0000, 0x0020, array('precis' => 'DISALLOWED')),
      new Uniprop(0x0020, 0x0021, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x0021, 0x007F, array('precis' => 'PVALID')),
      new Uniprop(0x007F, 0x00A0, array('precis' => 'DISALLOWED')),
      new Uniprop(0x00A0, 0x00AA, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x00AA, 0x00AB, array('precis' => 'PVALID')),
      new Uniprop(0x00AB, 0x00AD, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x00AD, 0x00AE, array('precis' => 'DISALLOWED')),
      new Uniprop(0x00AE, 0x00B7, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x00B7, 0x00B8, array('precis' => 'CONTEXTO')),
      new Uniprop(0x00B8, 0x00C0, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x00C0, 0x00D7, array('precis' => 'PVALID')),
      new Uniprop(0x00D7, 0x00D8, array('precis' => 'FREE_PVAL')),
      new Uniprop(0x00D8, 0x00F7, array('precis' => 'PVALID')),
      new Uniprop(0x00F7, 0x00F8, array('precis' => 'FREE_PVAL')),
    );

    $this->dumper->dump($this->stream, $extents);

    $expected = file_get_contents(dirname(__FILE__) . '/fixtures/draft-precis-expected-preg-class.txt');
    fseek($this->stream, 0);
    $result = stream_get_contents($this->stream);

    $this->assertEquals($expected, $result);
  }
}
