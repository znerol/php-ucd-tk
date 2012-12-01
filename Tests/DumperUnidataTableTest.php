<?php

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Dumper;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class DumperUnidataTableTest extends PHPUnit_Framework_TestCase
{
  private $srv;

  private $stream;

  private $dumper;

  public function setUp() {
    $this->srv = new DefaultServices(new Runner\Base());
    $this->stream = fopen("php://memory", "rw");
    $this->dumper = new Dumper\UnidataTable();
  }

  private function streamContentLines() {
    $result = '';

    fseek($this->stream, 0);
    while (($line = fgets($this->stream)) !== false) {
      if (strstr($line, ';')) {
        $result .= $line;
      }
    }

    return $result;
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

    $expected = file_get_contents(dirname(__FILE__) . '/fixtures/draft-precis-expected.txt');
    $result = $this->streamContentLines();
    $this->assertEquals($expected, $result);
  }

  public function testWriteBoolean() {
    $extents = array(
      new Uniprop(0x0009, 0x000E, array('White_Space' => true),  ' Cc   [5] <control-0009>..<control-000D>'),
      new Uniprop(0x0020, 0x0021, array('White_Space' => true),  ' Zs       SPACE'),
      new Uniprop(0x0085, 0x0086, array('White_Space' => true),  ' Cc       <control-0085>'),
      new Uniprop(0x00A0, 0x00A1, array('White_Space' => true),  ' Zs       NO-BREAK SPACE'),
      new Uniprop(0x1680, 0x1681, array('White_Space' => true),  ' Zs       OGHAM SPACE MARK'),
      new Uniprop(0x180E, 0x180F, array('White_Space' => true),  ' Zs       MONGOLIAN VOWEL SEPARATOR'),
      new Uniprop(0x2000, 0x200B, array('White_Space' => true),  ' Zs  [11] EN QUAD..HAIR SPACE'),
      new Uniprop(0x2028, 0x2029, array('White_Space' => true),  ' Zl       LINE SEPARATOR'),
      new Uniprop(0x2029, 0x202A, array('White_Space' => true),  ' Zp       PARAGRAPH SEPARATOR'),
      new Uniprop(0x202A, 0x202F, array('White_Space' => false), ' Cf   [5] LEFT-TO-RIGHT EMBEDDING..RIGHT-TO-LEFT OVERRIDE'),
      new Uniprop(0x202F, 0x2030, array('White_Space' => true),  ' Zs       NARROW NO-BREAK SPACE'),
      new Uniprop(0x205F, 0x2060, array('White_Space' => true),  ' Zs       MEDIUM MATHEMATICAL SPACE'),
      new Uniprop(0x3000, 0x3001, array('White_Space' => true),  ' Zs       IDEOGRAPHIC SPACE'),
    );

    $this->dumper->dump($this->stream, $extents);

    $expected = file_get_contents(dirname(__FILE__) . '/fixtures/PropListExcerpt.txt');
    $result = $this->streamContentLines();
    $this->assertEquals($expected, $result);
  }

  public function testReadWriteReread() {
    $fixture = dirname(__FILE__) . '/fixtures/Blocks.txt';

    // Reader and parser for reading the blocks file
    $reader = new Command\ReadTable($fixture);
    $parser = new Command\UnipropAll($reader, 'blk');

    // Parse using a standard runner
    $orig_extents = $this->srv->getRunner()->run($parser, $this->srv);
    $this->assertEquals(220, count($orig_extents));

    // Dump blocks to in-memory stream
    $this->dumper->dump($this->stream, $orig_extents);
    fseek($this->stream, 0);

    // Setup runner to return our own stream object when openURL is called.
    $runner = $this->getMock('Znerol\Unidata\Runner\Base', array('openURL'));
    $runner->expects($this->once())
      ->method('openURL')
      ->with($this->equalTo($fixture))
      ->will($this->returnValue($this->stream));
    $newsrv = new DefaultServices($runner);

    // Rerun parser
    $reparse_extents = $newsrv->getRunner()->run($parser, $newsrv);

    $this->assertEquals($orig_extents, $reparse_extents);
  }
}
