<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib;

use FontLib\Exception\FontNotFoundException;

/**
 * Generic font file.
 *
 * @package php-font-lib
 */
class Font {
  static $debug = false;

  /**
   * @param string $file The font file
   *
   * @return TrueType\File|null $file
   */
  public static function load($file) {
      if(!file_exists($file)){
          throw new FontNotFoundException($file);
      }

    $header = file_get_contents($file, false, null, 0, 4);
    $class  = null;

    switch ($header) {
      case "\x00\x01\x00\x00":
      case "true":
      case "typ1":
        $class = "TrueType\\File";
        break;

      case "OTTO":
        $class = "OpenType\\File";
        break;

      case "wOFF":
        $class = "WOFF\\File";
        break;

      case "ttcf":
        $class = "TrueType\\Collection";
        break;

      // Unknown type or EOT
      default:
        $magicNumber = file_get_contents($file, false, null, 34, 2);

        if ($magicNumber === "LP") {
          $class = "EOT\\File";
        }
    }

    if ($class) {
      $class = "FontLib\\$class";

      /** @var TrueType\File $obj */
      $obj = new $class;
      $obj->load($file);

      return $obj;
    }

    return null;
  }

  static function d($str) {
    if (!self::$debug) {
      return;
    }
    echo "$str\n";
  }

  static function UTF16ToUTF8($str) {
    return mb_convert_encoding($str, "utf-8", "utf-16");
  }

  static function UTF8ToUTF16($str) {
    return mb_convert_encoding($str, "utf-16", "utf-8");
  }
}
