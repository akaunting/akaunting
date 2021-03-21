<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib\WOFF;

use FontLib\Table\DirectoryEntry;

/**
 * WOFF font file.
 *
 * @package php-font-lib
 *
 * @property TableDirectoryEntry[] $directory
 */
class File extends \FontLib\TrueType\File {
  function parseHeader() {
    if (!empty($this->header)) {
      return;
    }

    $this->header = new Header($this);
    $this->header->parse();
  }

  public function load($file) {
    parent::load($file);

    $this->parseTableEntries();
    $dataOffset = $this->pos() + count($this->directory) * 20;

    $fw = $this->getTempFile(false);
    $fr = $this->f;

    $this->f = $fw;
    $offset  = $this->header->encode();

    foreach ($this->directory as $entry) {
      // Read ...
      $this->f = $fr;
      $this->seek($entry->offset);
      $data = $this->read($entry->length);

      if ($entry->length < $entry->origLength) {
        $data = gzuncompress($data);
      }

      // Prepare data ...
      $length        = strlen($data);
      $entry->length = $entry->origLength = $length;
      $entry->offset = $dataOffset;

      // Write ...
      $this->f = $fw;

      // Woff Entry
      $this->seek($offset);
      $offset += $this->write($entry->tag, 4); // tag
      $offset += $this->writeUInt32($dataOffset); // offset
      $offset += $this->writeUInt32($length); // length
      $offset += $this->writeUInt32($length); // origLength
      $offset += $this->writeUInt32(DirectoryEntry::computeChecksum($data)); // checksum

      // Data
      $this->seek($dataOffset);
      $dataOffset += $this->write($data, $length);
    }

    $this->f = $fw;
    $this->seek(0);

    // Need to re-parse this, don't know why
    $this->header    = null;
    $this->directory = array();
    $this->parseTableEntries();
  }
}
