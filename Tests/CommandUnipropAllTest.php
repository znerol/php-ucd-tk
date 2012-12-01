<?php

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Runner;
use Znerol\Unidata\Uniprop;

class CommandUnipropAllTest extends PHPUnit_Framework_TestCase
{
  private $srv;

  private $reader;

  public function setUp() {
    $this->srv = new DefaultServices(new Runner\Base());

    // First lines of PropList-6.2.0.txt after tokenizing.
    $data = array(
      //    $start, $end,   $fields,                             $comment
      array(0x0009, 0x000D, array('0009..000D', 'White_Space'),  ' Cc   [5] <control-0009>..<control-000D>'),
      array(0x0020, false,  array('0020',       'White_Space'),  ' Zs       SPACE'),
      array(0x0085, false,  array('0085',       'White_Space'),  ' Cc       <control-0085>'),
      array(0x00A0, false,  array('00A0',       'White_Space'),  ' Zs       NO-BREAK SPACE'),
      array(0x1680, false,  array('1680',       'White_Space'),  ' Zs       OGHAM SPACE MARK'),
      array(0x180E, false,  array('180E',       'White_Space'),  ' Zs       MONGOLIAN VOWEL SEPARATOR'),
      array(0x2000, 0x200A, array('2000..200A', 'White_Space'),  ' Zs  [11] EN QUAD..HAIR SPACE'),
      array(0x2028, false,  array('2028',       'White_Space'),  ' Zl       LINE SEPARATOR'),
      array(0x2029, false,  array('2029',       'White_Space'),  ' Zp       PARAGRAPH SEPARATOR'),
      array(0x202F, false,  array('202F',       'White_Space'),  ' Zs       NARROW NO-BREAK SPACE'),
      array(0x205F, false,  array('205F',       'White_Space'),  ' Zs       MEDIUM MATHEMATICAL SPACE'),
      array(0x3000, false,  array('3000',       'White_Space'),  ' Zs       IDEOGRAPHIC SPACE'),
      array(0x200E, 0x200F, array('200E..200F', 'Bidi_Control'), ' Cf   [2] LEFT-TO-RIGHT MARK..RIGHT-TO-LEFT MARK'),
      array(0x202A, 0x202E, array('202A..202E', 'Bidi_Control'), ' Cf   [5] LEFT-TO-RIGHT EMBEDDING..RIGHT-TO-LEFT OVERRIDE'),
      array(0x200C, 0x200D, array('200C..200D', 'Join_Control'), ' Cf   [2] ZERO WIDTH NON-JOINER..ZERO WIDTH JOINER'),
    );
    $this->reader = $this->getMock('Znerol\Unidata\Command');
    $this->reader
      ->expects($this->any())
      ->method('run')
      ->will($this->returnValue($data));
  }

  public function testParsePropListData() {
    $command = new Command\UnipropAll($this->reader, 'TESTPROP');

    // First lines of PropList-6.2.0.txt after tokenizing.
    $expected = array(
      //          $head,  $next,  $props,                              $comment
      new Uniprop(0x0009, 0x000E, array('TESTPROP' => 'White_Space'),  ' Cc   [5] <control-0009>..<control-000D>'),
      new Uniprop(0x0020, 0x0021, array('TESTPROP' => 'White_Space'),  ' Zs       SPACE'),
      new Uniprop(0x0085, 0x0086, array('TESTPROP' => 'White_Space'),  ' Cc       <control-0085>'),
      new Uniprop(0x00A0, 0x00A1, array('TESTPROP' => 'White_Space'),  ' Zs       NO-BREAK SPACE'),
      new Uniprop(0x1680, 0x1681, array('TESTPROP' => 'White_Space'),  ' Zs       OGHAM SPACE MARK'),
      new Uniprop(0x180E, 0x180F, array('TESTPROP' => 'White_Space'),  ' Zs       MONGOLIAN VOWEL SEPARATOR'),
      new Uniprop(0x2000, 0x200B, array('TESTPROP' => 'White_Space'),  ' Zs  [11] EN QUAD..HAIR SPACE'),

      // Despite being adjacent the following two lines do not get merged 
      // because the property value differs.
      new Uniprop(0x200C, 0x200E, array('TESTPROP' => 'Join_Control'), ' Cf   [2] ZERO WIDTH NON-JOINER..ZERO WIDTH JOINER'),
      new Uniprop(0x200E, 0x2010, array('TESTPROP' => 'Bidi_Control'), ' Cf   [2] LEFT-TO-RIGHT MARK..RIGHT-TO-LEFT MARK'),

      // The following two lines get merged.
      //   new Uniprop(0x2028, 0x2029, array('TESTPROP' => 'White_Space'),  ' Zl       LINE SEPARATOR'),
      //   new Uniprop(0x2029, 0x202A, array('TESTPROP' => 'White_Space'),  ' Zp       PARAGRAPH SEPARATOR'),
      new Uniprop(0x2028, 0x202A, array('TESTPROP' => 'White_Space'),  ' Zl       LINE SEPARATOR'),

      new Uniprop(0x202A, 0x202F, array('TESTPROP' => 'Bidi_Control'), ' Cf   [5] LEFT-TO-RIGHT EMBEDDING..RIGHT-TO-LEFT OVERRIDE'),
      new Uniprop(0x202F, 0x2030, array('TESTPROP' => 'White_Space'),  ' Zs       NARROW NO-BREAK SPACE'),
      new Uniprop(0x205F, 0x2060, array('TESTPROP' => 'White_Space'),  ' Zs       MEDIUM MATHEMATICAL SPACE'),
      new Uniprop(0x3000, 0x3001, array('TESTPROP' => 'White_Space'),  ' Zs       IDEOGRAPHIC SPACE'),
    );

    $result = $this->srv->getRunner()->run($command, $this->srv);
    $this->assertEquals($expected, $result);
  }

  public function testParseWithCustomComment() {
    $command = new Command\UnipropAll($this->reader, 'OTHERPROP', 'COMMENT');

    // First lines of PropList-6.2.0.txt after tokenizing.
    $expected = array(
      //          $head,  $next,  $props,                              $comment
      new Uniprop(0x0009, 0x000E, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x0020, 0x0021, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x0085, 0x0086, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x00A0, 0x00A1, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x1680, 0x1681, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x180E, 0x180F, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x2000, 0x200B, array('OTHERPROP' => 'White_Space'),  'COMMENT'),

      // Despite being adjacent the following two lines do not get merged 
      // because the property value differs.
      new Uniprop(0x200C, 0x200E, array('OTHERPROP' => 'Join_Control'), 'COMMENT'),
      new Uniprop(0x200E, 0x2010, array('OTHERPROP' => 'Bidi_Control'), 'COMMENT'),

      // The following two lines get merged.
      //   new Uniprop(0x2028, 0x2029, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      //   new Uniprop(0x2029, 0x202A, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x2028, 0x202A, array('OTHERPROP' => 'White_Space'),  'COMMENT'),

      new Uniprop(0x202A, 0x202F, array('OTHERPROP' => 'Bidi_Control'), 'COMMENT'),
      new Uniprop(0x202F, 0x2030, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x205F, 0x2060, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
      new Uniprop(0x3000, 0x3001, array('OTHERPROP' => 'White_Space'),  'COMMENT'),
    );

    $result = $this->srv->getRunner()->run($command, $this->srv);
    $this->assertEquals($expected, $result);
  }
}
