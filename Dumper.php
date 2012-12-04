<?php
/**
 * @file
 * Definition of Znerol::Unidata::Dumper.
 */

namespace Znerol\Unidata;

/**
 * Interface for classes capable of serializing a set of extents to a stream.
 */
interface Dumper
{
  /**
   * Serialize extents and write them to the given stream.
   *
   * @param Stream $stream
   *   An output stream where the serialized extents will be written to.
   *
   * @param array $extents
   *   An array of Uniprop objects.
   */
  public function dump($stream, $extents);
}
