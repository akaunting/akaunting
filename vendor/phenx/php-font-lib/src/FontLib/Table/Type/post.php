<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib\Table\Type;
use FontLib\Table\Table;
use FontLib\TrueType\File;

/**
 * `post` font table.
 *
 * @package php-font-lib
 */
class post extends Table {
  protected $def = array(
    "format"             => self::Fixed,
    "italicAngle"        => self::Fixed,
    "underlinePosition"  => self::FWord,
    "underlineThickness" => self::FWord,
    "isFixedPitch"       => self::uint32,
    "minMemType42"       => self::uint32,
    "maxMemType42"       => self::uint32,
    "minMemType1"        => self::uint32,
    "maxMemType1"        => self::uint32,
  );

  protected function _parse() {
    $font = $this->getFont();
    $data = $font->unpack($this->def);

    $names = array();

    switch ($data["format"]) {
      case 1:
        $names = File::$macCharNames;
        break;

      case 2:
        $data["numberOfGlyphs"] = $font->readUInt16();

        $glyphNameIndex = $font->readUInt16Many($data["numberOfGlyphs"]);

        $data["glyphNameIndex"] = $glyphNameIndex;

        $namesPascal = array();
        for ($i = 0; $i < $data["numberOfGlyphs"]; $i++) {
          $len           = $font->readUInt8();
          $namesPascal[] = $font->read($len);
        }

        foreach ($glyphNameIndex as $g => $index) {
          if ($index < 258) {
            $names[$g] = File::$macCharNames[$index];
          }
          else {
            if (array_key_exists($index - 258, $namesPascal)) {
              $names[$g] = $namesPascal[$index - 258];
            }
          }
        }

        break;

      case 2.5:
        // TODO
        break;

      case 3:
        // nothing
        break;

      case 4:
        // TODO
        break;
    }

    $data["names"] = $names;

    $this->data = $data;
  }

  function _encode() {
    $font           = $this->getFont();
    $data           = $this->data;
    $data["format"] = 3;

    $length = $font->pack($this->def, $data);

    return $length;
    /*
    $subset = $font->getSubset();

    switch($data["format"]) {
      case 1:
        // nothing to do
      break;

      case 2:
        $old_names = $data["names"];

        $glyphNameIndex = range(0, count($subset));

        $names = array();
        foreach($subset as $gid) {
          $names[] = $data["names"][$data["glyphNameIndex"][$gid]];
        }

        $numberOfGlyphs = count($names);
        $length += $font->writeUInt16($numberOfGlyphs);

        foreach($glyphNameIndex as $gni) {
          $length += $font->writeUInt16($gni);
        }

        //$names = array_slice($names, 257);
        foreach($names as $name) {
          $len = strlen($name);
          $length += $font->writeUInt8($len);
          $length += $font->write($name, $len);
        }

      break;

      case 2.5:
        // TODO
      break;

      case 3:
        // nothing
      break;

      case 4:
        // TODO
      break;
    }

    return $length;*/
  }
}