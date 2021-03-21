<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib\TrueType;

use Countable;
use FontLib\BinaryStream;
use Iterator;
use OutOfBoundsException;

/**
 * TrueType collection font file.
 *
 * @package php-font-lib
 */
class Collection extends BinaryStream implements Iterator, Countable {
  /**
   * Current iterator position.
   *
   * @var integer
   */
  private $position = 0;

  protected $collectionOffsets = array();
  protected $collection = array();
  protected $version;
  protected $numFonts;

  function parse() {
    if (isset($this->numFonts)) {
      return;
    }

    $this->read(4); // tag name

    $this->version  = $this->readFixed();
    $this->numFonts = $this->readUInt32();

    for ($i = 0; $i < $this->numFonts; $i++) {
      $this->collectionOffsets[] = $this->readUInt32();
    }
  }

  /**
   * @param int $fontId
   *
   * @throws OutOfBoundsException
   * @return File
   */
  function getFont($fontId) {
    $this->parse();

    if (!isset($this->collectionOffsets[$fontId])) {
      throw new OutOfBoundsException();
    }

    if (isset($this->collection[$fontId])) {
      return $this->collection[$fontId];
    }

    $font    = new File();
    $font->f = $this->f;
    $font->setTableOffset($this->collectionOffsets[$fontId]);

    return $this->collection[$fontId] = $font;
  }

  function current() {
    return $this->getFont($this->position);
  }

  function key() {
    return $this->position;
  }

  function next() {
    return ++$this->position;
  }

  function rewind() {
    $this->position = 0;
  }

  function valid() {
    $this->parse();

    return isset($this->collectionOffsets[$this->position]);
  }

  function count() {
    $this->parse();

    return $this->numFonts;
  }
}
