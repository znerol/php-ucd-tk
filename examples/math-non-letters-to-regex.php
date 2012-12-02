<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Znerol\Unidata;

class ExtractMath extends Unidata\Command\UnipropAll {
  protected function getProps($start, $end, $fields, $comment) {
    if ($fields[1] == 'Math') {
      return array(
        'non_alpha_math' => True,
      );
    }
  }
}

class ExtractAlpha extends Unidata\Command\UnipropAll {
  protected function getProps($start, $end, $fields, $comment) {
    if ($fields[1] == 'Alphabetic') {
      return parent::getProps($start, $end, $fields, $comment);
    }
  }
}

$srv = new Unidata\DefaultServices(new Unidata\Runner\Base());

$reader = new Unidata\Command\ReadTable(
  'http://www.unicode.org/Public/UNIDATA/DerivedCoreProperties.txt');

$math_extents = $srv->getRunner()->run(
  new ExtractMath($reader, 'Non_Alpha_Math', 'math rule'), $srv);
$alpha_extents = $srv->getRunner()->run(
  new ExtractAlpha($reader, 'Non_Alpha_Math', 'alpha rule'), $srv);

$non_alpha_math_extents = $srv->getSet()->difference($math_extents, $alpha_extents);

$dumper = new Unidata\Dumper\PHPPreg('my\ns\NonAlphExtentsPattern', $srv->getSet(), new Unidata\Extent\Base\PregBuilder());
$dumper->dump(fopen("php://stdout", "w"), $non_alpha_math_extents);
