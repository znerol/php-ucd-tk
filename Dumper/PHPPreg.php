<?php
/**
 * @file
 * Definition of Znerol::Unidata::Dumper::PHPPreg.
 */

namespace Znerol\Unidata\Dumper;

use Znerol\Unidata\Uniprop;
use Znerol\Unidata\Extent\Base;

/**
 * Write property extents to a stream as a PHP class containing preg patterns
 * as constant members.
 */
class PHPPreg implements \Znerol\Unidata\Dumper {
  /**
   * Name of resulting class including its namespace.
   */
  private $classname;

  /**
   * Unicode property extent sets operation service instance.
   */
  private $set;

  /**
   * Instance capable of converting extent sets to preg regex patterns.
   */
  private $builder;

  /**
   * Create a PHP preg writer instance.
   *
   * @param string $classname
   *   Name of resulting class including its namespace.
   *
   * @param Uniprop::Set $set
   *   Unicode property extent sets operation service instance
   *
   * @param Extent::Base::PregBuilder $builder
   *   Instance capable of converting extent sets to preg regex patterns.
   */
  public function __construct($classname, Uniprop\Set $set, Base\PregBuilder $builder) {
    $this->classname = $classname;
    $this->set = $set;
    $this->builder = $builder;
  }

  /**
   * Dump the specified sorted extents to a PHP class.
   *
   * @param Stream $stream
   *   File object where serialized extents are written to.
   * @param array $extents
   *   List of Uniprop objects to write.
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
