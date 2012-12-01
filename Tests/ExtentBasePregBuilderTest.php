<?php

use Znerol\Unidata\Extent\Base;

class ExtentBasePregBuilderTest extends PHPUnit_Framework_TestCase
{
  private $dumper;

  public function setUp() {
    $this->builder = new Base\PregBuilder();
  }

  public function testWriteSimple() {
    $disallowed = array(
      new Base\Extent(0x0000, 0x0020),
      new Base\Extent(0x007F, 0x00A0),
      new Base\Extent(0x00AD, 0x00AE),
    );

    $pvalid = array(
      new Base\Extent(0x0021, 0x007F),
      new Base\Extent(0x00AA, 0x00AB),
      new Base\Extent(0x00C0, 0x00D7),
      new Base\Extent(0x00D8, 0x00F7),
    );

    $ccdisallowed = $this->builder->charclass($disallowed);
    $this->assertEquals('\x{0}-\x{1F}\x{7F}-\x{9F}\x{AD}', $ccdisallowed);
    $subdisallowed = $this->builder->subpattern($ccdisallowed, 'DISALLOWED');
    $this->assertEquals('(?P<DISALLOWED>[' . $ccdisallowed . ']+)', $subdisallowed);


    $ccpvalid = $this->builder->charclass($pvalid);
    $this->assertEquals('\x{21}-\x{7E}\x{AA}\x{C0}-\x{D6}\x{D8}-\x{F6}', $ccpvalid);
    $subpvalid = $this->builder->subpattern($ccpvalid, 'PVALID');
    $this->assertEquals('(?P<PVALID>[' . $ccpvalid . ']+)', $subpvalid);

    $pattern = $this->builder->pattern(array($subdisallowed, $subpvalid));

    $this->assertEquals('#' . $subdisallowed . '|' . $subpvalid . '#uS', $pattern);

    // Test subpattern-negative flag
    $subpvalidneg = $this->builder->subpattern($ccpvalid, 'PVALIDNEG', TRUE);
    $this->assertEquals('(?P<PVALIDNEG>[^' . $ccpvalid . ']+)', $subpvalidneg);
  }
}
