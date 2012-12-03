<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Dumper;
use Znerol\Unidata\Extent\Base\PregBuilder;

class ExtractMath extends Command\UnipropAll {
  protected function getProps($start, $end, $fields, $comment) {
    if ($fields[1] == 'Math') {
      return array(
        'non_alpha_math' => True,
      );
    }
  }
}

$srv = new DefaultServices();
$runner = new Runner\Base($srv);

$reader = new Command\ReadTable(
  'http://www.unicode.org/Public/UNIDATA/DerivedCoreProperties.txt');

$math_extents = $runner->run(
  new ExtractMath($reader, 'Non_Alpha_Math', 'math rule'));

$alpha_extents = $runner->run(new Command\UnipropCallback($reader, function($start, $end, $fields, $comment) {
  if ($fields[1] == 'Alphabetic') {
    return array('alpha' => true);
  }
}));

$set = $srv->getSet();
$non_alpha_math_extents = $set->difference($math_extents, $alpha_extents);

$dumper = new Dumper\PHPPreg('my\ns\NonAlphExtentsPattern', $set, new PregBuilder());
$dumper->dump(fopen("php://stdout", "w"), $non_alpha_math_extents);
