<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Znerol\Unidata\Command;
use Znerol\Unidata\DefaultServices;
use Znerol\Unidata\Dumper;
use Znerol\Unidata\Extent\Base\PregBuilder;
use Znerol\Unidata\Fetcher;
use Znerol\Unidata\Runner;

$stderr = fopen("php://stderr", "r");

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
$srv->setFetcher(new Fetcher\Caching());
$runner = new Runner\Caching($srv);

$reader = new Command\ReadTable(
  'http://www.unicode.org/Public/UNIDATA/DerivedCoreProperties.txt');

fprintf($stderr, "Fetch DerivedCoreProperties.txt and extract math characters\n");
$math_extents = $runner->run(
  new ExtractMath($reader, 'Non_Alpha_Math', 'math rule'));

fprintf($stderr, "Fetch DerivedCoreProperties.txt and extract alphabetic characters\n");
$alpha_extents = $runner->run(new Command\UnipropCallback($reader, function($start, $end, $fields, $comment) {
  if ($fields[1] == 'Alphabetic') {
    return array('alpha' => true);
  }
}));

fprintf($stderr, "Subtracting alphabetic characters from math\n");
$set = $srv->getSet();
$non_alpha_math_extents = $set->difference($math_extents, $alpha_extents);

fprintf($stderr, "Dumping PHP class\n");
$dumper = new Dumper\PHPPreg('my\ns\NonAlphExtentsPattern', $set, new PregBuilder());
$dumper->dump(fopen("php://stdout", "w"), $non_alpha_math_extents);
