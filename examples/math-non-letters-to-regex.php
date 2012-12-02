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

$srv = new Unidata\DefaultServices();

$reader = new Unidata\Command\ReadTable(
  'http://www.unicode.org/Public/UNIDATA/DerivedCoreProperties.txt');

$math_extents = $srv->run(
  new ExtractMath($reader, 'Non_Alpha_Math', 'math rule'));

$alpha_extents = $srv->run(new Unidata\Command\UnipropCallback($reader, function($start, $end, $fields, $comment) {
  if ($fields[1] == 'Alphabetic') {
    return array('alpha' => true);
  }
}));

$set = $srv->getSet();
$non_alpha_math_extents = $srv->difference($math_extents, $alpha_extents);

$dumper = new Unidata\Dumper\PHPPreg('my\ns\NonAlphExtentsPattern', $set, new Unidata\Extent\Base\PregBuilder());
$dumper->dump(fopen("php://stdout", "w"), $non_alpha_math_extents);
