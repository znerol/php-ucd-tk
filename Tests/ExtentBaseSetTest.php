<?php

use Znerol\Unidata\Extent\Set;
use Znerol\Unidata\Extent\Base;

class ExtentsTest extends PHPUnit_Framework_TestCase
{
  private $extents;

  public function setUp() {
    $this->set = new Set(new Base\Operations());
  }

  public function testUnionEmptyExtents()
  {
    $s1 = array();
    $s2 = array();
    $result = $this->set->union($s1, $s2);

    $this->assertEquals(array(), $result);
  }

  public function testUnionEmptyExtentsToExisting()
  {
    $s1 = array(new Base\Extent(0, 1));
    $s2 = array();
    $result = $this->set->union($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 1)), $result);
  }

  public function testUnionDisjointExtents()
  {
    $s1 = array(new Base\Extent(0, 1));
    $s2 = array(new Base\Extent(2, 1));
    $result = $this->set->union($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 1), new Base\Extent(2, 1)), $result);
  }

  public function testUnionAdjacentExtents()
  {
    $s1 = array(new Base\Extent(0, 2));
    $s2 = array(new Base\Extent(2, 3));
    $result = $this->set->union($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 3)), $result);
  }

  public function testUnionOverlappingExtents()
  {
    $s1 = array(new Base\Extent(0, 2));
    $s2 = array(new Base\Extent(1, 3));
    $result = $this->set->union($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 3)), $result);
  }

  public function testDiffEmptyExtentss()
  {
    $s1 = array();
    $s2 = array();
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(), $result);
  }

  public function testDifferenceEmptyExtentsFromExisting()
  {
    $s1 = array(new Base\Extent(0, 1));
    $s2 = array();
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 1)), $result);
  }

  public function testDifferenceExistingFromEmptyExtents()
  {
    $s1 = array();
    $s2 = array(new Base\Extent(0, 1));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(), $result);
  }

  public function testDifferenceSameSections()
  {
    $s1 = array(new Base\Extent(0, 1));
    $s2 = array(new Base\Extent(0, 1));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(), $result);
  }

  public function testDifferenceAdjacentSection()
  {
    $s1 = array(new Base\Extent(0, 2));
    $s2 = array(new Base\Extent(2, 1));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 2)), $result);
  }

  public function testDifferenceDisjointSection()
  {
    $s1 = array(new Base\Extent(0, 1));
    $s2 = array(new Base\Extent(3, 1));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 1)), $result);

    $s1 = array(new Base\Extent(3, 1));
    $s2 = array(new Base\Extent(0, 1));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(new Base\Extent(3, 1)), $result);
  }

  public function testDifferenceOverlappingSection()
  {
    $s1 = (array(new Base\Extent(0, 3)));
    $s2 = (array(new Base\Extent(2, 4)));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(new Base\Extent(0, 2)), $result);
  }

  public function testDifferenceBigFromSeveralSmallSections()
  {
    $s1 = (array(
      new Base\Extent(0, 7),
      new Base\Extent(10, 16),
    ));
    $s2 = (array(new Base\Extent(0, 26)));
    $result = $this->set->difference($s1, $s2);

    $this->assertEquals(array(), $result);
  }
}
