<?php

use Znerol\Unidata\Uniprop;

class UnipropOperationsTest extends PHPUnit_Framework_TestCase
{
  private $extents;

  public function setUp() {
    $this->ops = new Uniprop\Operations();
  }

  public function testJoinWithEqualProperties() {
    $props = array(
      'key' => 'value',
    );

    $result = $this->ops->join(
      new Uniprop(4, 7, $props),
      new Uniprop(7, 9, $props));
    $expected = array(
      new Uniprop(4, 9, $props));

    $this->assertEquals($expected, $result);
  }

  public function testJoinWithNonEqualProperties() {
    $props1 = array(
      'key' => 'value',
    );
    $props2 = array(
      'otherkey' => 'value',
    );

    $result = $this->ops->join(
      new Uniprop(4, 7, $props1),
      new Uniprop(7, 9, $props2));
    $expected = array(
      new Uniprop(4, 7, $props1),
      new Uniprop(7, 9, $props2));

    $this->assertEquals($expected, $result);
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testJoinOverlappingWithNonEqualProperties() {
    $props1 = array(
      'key' => 'value',
    );
    $props2 = array(
      'otherkey' => 'value',
    );

    $result = $this->ops->join(
      new Uniprop(4, 8, $props1),
      new Uniprop(7, 9, $props2));
  }


  public function testSplitWithEqualProperties() {
    $props = array(
      'key' => 'value',
    );

    $result = $this->ops->split(
      new Uniprop(4, 7, $props),
      new Uniprop(5, 6, $props));
    $expected = array(
      new Uniprop(4, 5, $props),
      new Uniprop(6, 7, $props),
    );

    $this->assertEquals($expected, $result);
  }


  public function testSplitWithNonEqualProperties() {
    $props1 = array(
      'key' => 'value',
    );
    $props2 = array(
      'otherkey' => 'value',
    );

    $result = $this->ops->split(
      new Uniprop(4, 8, $props1),
      new Uniprop(7, 9, $props2));
    $expected = array(
      new Uniprop(4, 7, $props1),
    );

    $this->assertEquals($expected, $result);
  }
}
