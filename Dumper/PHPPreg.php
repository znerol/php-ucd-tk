<?php

namespace Znerol\Unidata\Dumper;

use Znerol\Unidata\Dumper;
use Znerol\Unidata\Uniprop;
use Znerol\Unidata\Extent\Base;

class PHPPreg implements Dumper {
  private $classname;

  private $set;

  private $builder;

  public function __construct($classname, Uniprop\Set $set, Base\PregBuilder $builder) {
    $this->classname = $classname;
    $this->set = $set;
    $this->builder = $builder;
  }

  /**
   * Dump the specified sorted extents to a PHP class.
   */
  public function dump($stream, $extents) {
    // Ensure that extents do not have any overlaps.
    if ($this->set->firstOverlap($extents) !== false) {
      throw new \InvalidArgumentException('Cannot build preg patterns from overlapping extents');
    }

    fwrite($stream, "<?php\n\n");

    $ns = explode('\\', $this->classname);
    $name = array_pop($ns);
    if (!empty($ns)) {
      fwrite($stream, sprintf("namespace %s;\n\n", implode('\\', $ns)));
    }

    fwrite($stream, sprintf("class %s {\n", $name));

    $boolprops = array();
    $groups = $this->set->group($extents, $boolprops);

    $subpatterns = array();
    foreach ($groups as $propname => $propvals) {
      foreach ($propvals as $propval => $extents) {
        $groupname = $boolprops[$propname] ? $propname : $propname . '_' . $propval;
        $groupname = strtoupper($groupname);
        $groupname = preg_replace('#[^A-Z0-9_]#', '', $groupname);
        $groupname = ltrim($groupname, '0123456789');
        if ($groupname == "" || $groupname == "_") {
          throw new \Exception('No valid characters in group name');
        }

        $charclass = $this->builder->charclass($extents);
        $subpatterns[] = $this->builder->subpattern($charclass, $groupname);

        fwrite($stream, sprintf("    const CHARCLASS_%s = '%s';\n", $groupname, $charclass));
      }
    }

    if (!empty($subpatterns)) {
      $pattern = $this->builder->pattern($subpatterns);
      fwrite($stream, sprintf("    const PATTERN = '%s';\n", $pattern));
    }

    fwrite($stream, "}\n");
  }
}
