<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib\TrueType;

use FontLib\AdobeFontMetrics;
use FontLib\Font;
use FontLib\BinaryStream;
use FontLib\Table\Table;
use FontLib\Table\DirectoryEntry;
use FontLib\Table\Type\glyf;
use FontLib\Table\Type\name;
use FontLib\Table\Type\nameRecord;

/**
 * TrueType font file.
 *
 * @package php-font-lib
 */
class File extends BinaryStream {
  /**
   * @var Header
   */
  public $header = array();

  private $tableOffset = 0; // Used for TTC

  private static $raw = false;

  protected $directory = array();
  protected $data = array();

  protected $glyph_subset = array();

  public $glyph_all = array();

  static $macCharNames = array(
    ".notdef", ".null", "CR",
    "space", "exclam", "quotedbl", "numbersign",
    "dollar", "percent", "ampersand", "quotesingle",
    "parenleft", "parenright", "asterisk", "plus",
    "comma", "hyphen", "period", "slash",
    "zero", "one", "two", "three",
    "four", "five", "six", "seven",
    "eight", "nine", "colon", "semicolon",
    "less", "equal", "greater", "question",
    "at", "A", "B", "C", "D", "E", "F", "G",
    "H", "I", "J", "K", "L", "M", "N", "O",
    "P", "Q", "R", "S", "T", "U", "V", "W",
    "X", "Y", "Z", "bracketleft",
    "backslash", "bracketright", "asciicircum", "underscore",
    "grave", "a", "b", "c", "d", "e", "f", "g",
    "h", "i", "j", "k", "l", "m", "n", "o",
    "p", "q", "r", "s", "t", "u", "v", "w",
    "x", "y", "z", "braceleft",
    "bar", "braceright", "asciitilde", "Adieresis",
    "Aring", "Ccedilla", "Eacute", "Ntilde",
    "Odieresis", "Udieresis", "aacute", "agrave",
    "acircumflex", "adieresis", "atilde", "aring",
    "ccedilla", "eacute", "egrave", "ecircumflex",
    "edieresis", "iacute", "igrave", "icircumflex",
    "idieresis", "ntilde", "oacute", "ograve",
    "ocircumflex", "odieresis", "otilde", "uacute",
    "ugrave", "ucircumflex", "udieresis", "dagger",
    "degree", "cent", "sterling", "section",
    "bullet", "paragraph", "germandbls", "registered",
    "copyright", "trademark", "acute", "dieresis",
    "notequal", "AE", "Oslash", "infinity",
    "plusminus", "lessequal", "greaterequal", "yen",
    "mu", "partialdiff", "summation", "product",
    "pi", "integral", "ordfeminine", "ordmasculine",
    "Omega", "ae", "oslash", "questiondown",
    "exclamdown", "logicalnot", "radical", "florin",
    "approxequal", "increment", "guillemotleft", "guillemotright",
    "ellipsis", "nbspace", "Agrave", "Atilde",
    "Otilde", "OE", "oe", "endash",
    "emdash", "quotedblleft", "quotedblright", "quoteleft",
    "quoteright", "divide", "lozenge", "ydieresis",
    "Ydieresis", "fraction", "currency", "guilsinglleft",
    "guilsinglright", "fi", "fl", "daggerdbl",
    "periodcentered", "quotesinglbase", "quotedblbase", "perthousand",
    "Acircumflex", "Ecircumflex", "Aacute", "Edieresis",
    "Egrave", "Iacute", "Icircumflex", "Idieresis",
    "Igrave", "Oacute", "Ocircumflex", "applelogo",
    "Ograve", "Uacute", "Ucircumflex", "Ugrave",
    "dotlessi", "circumflex", "tilde", "macron",
    "breve", "dotaccent", "ring", "cedilla",
    "hungarumlaut", "ogonek", "caron", "Lslash",
    "lslash", "Scaron", "scaron", "Zcaron",
    "zcaron", "brokenbar", "Eth", "eth",
    "Yacute", "yacute", "Thorn", "thorn",
    "minus", "multiply", "onesuperior", "twosuperior",
    "threesuperior", "onehalf", "onequarter", "threequarters",
    "franc", "Gbreve", "gbreve", "Idot",
    "Scedilla", "scedilla", "Cacute", "cacute",
    "Ccaron", "ccaron", "dmacron"
  );

  function getTable() {
    $this->parseTableEntries();

    return $this->directory;
  }

  function setTableOffset($offset) {
    $this->tableOffset = $offset;
  }

  function parse() {
    $this->parseTableEntries();

    $this->data = array();

    foreach ($this->directory as $tag => $table) {
      if (empty($this->data[$tag])) {
        $this->readTable($tag);
      }
    }
  }

  function utf8toUnicode($str) {
    $len = strlen($str);
    $out = array();

    for ($i = 0; $i < $len; $i++) {
      $uni = -1;
      $h   = ord($str[$i]);

      if ($h <= 0x7F) {
        $uni = $h;
      }
      elseif ($h >= 0xC2) {
        if (($h <= 0xDF) && ($i < $len - 1)) {
          $uni = ($h & 0x1F) << 6 | (ord($str[++$i]) & 0x3F);
        }
        elseif (($h <= 0xEF) && ($i < $len - 2)) {
          $uni = ($h & 0x0F) << 12 | (ord($str[++$i]) & 0x3F) << 6 | (ord($str[++$i]) & 0x3F);
        }
        elseif (($h <= 0xF4) && ($i < $len - 3)) {
          $uni = ($h & 0x0F) << 18 | (ord($str[++$i]) & 0x3F) << 12 | (ord($str[++$i]) & 0x3F) << 6 | (ord($str[++$i]) & 0x3F);
        }
      }

      if ($uni >= 0) {
        $out[] = $uni;
      }
    }

    return $out;
  }

  function getUnicodeCharMap() {
    $subtable = null;
    foreach ($this->getData("cmap", "subtables") as $_subtable) {
      if ($_subtable["platformID"] == 0 || $_subtable["platformID"] == 3 && $_subtable["platformSpecificID"] == 1) {
        $subtable = $_subtable;
        break;
      }
    }

    if ($subtable) {
      return $subtable["glyphIndexArray"];
    }

    return null;
  }

  function setSubset($subset) {
    if (!is_array($subset)) {
      $subset = $this->utf8toUnicode($subset);
    }

    $subset = array_unique($subset);

    $glyphIndexArray = $this->getUnicodeCharMap();

    if (!$glyphIndexArray) {
      return;
    }

    $gids = array(
      0, // .notdef
      1, // .null
    );

    foreach ($subset as $code) {
      if (!isset($glyphIndexArray[$code])) {
        continue;
      }

      $gid        = $glyphIndexArray[$code];
      $gids[$gid] = $gid;
    }

    /** @var glyf $glyf */
    $glyf = $this->getTableObject("glyf");
    $gids = $glyf->getGlyphIDs($gids);

    sort($gids);

    $this->glyph_subset = $gids;
    $this->glyph_all    = array_values($glyphIndexArray); // FIXME
  }

  function getSubset() {
    if (empty($this->glyph_subset)) {
      return $this->glyph_all;
    }

    return $this->glyph_subset;
  }

  function encode($tags = array()) {
    if (!self::$raw) {
      $tags = array_merge(array("head", "hhea", "cmap", "hmtx", "maxp", "glyf", "loca", "name", "post"), $tags);
    }
    else {
      $tags = array_keys($this->directory);
    }

    $num_tables = count($tags);
    $n          = 16; // @todo

    Font::d("Tables : " . implode(", ", $tags));

    /** @var DirectoryEntry[] $entries */
    $entries = array();
    foreach ($tags as $tag) {
      if (!isset($this->directory[$tag])) {
        Font::d("  >> '$tag' table doesn't exist");
        continue;
      }

      $entries[$tag] = $this->directory[$tag];
    }

    $this->header->data["numTables"] = $num_tables;
    $this->header->encode();

    $directory_offset = $this->pos();
    $offset           = $directory_offset + $num_tables * $n;
    $this->seek($offset);

    $i = 0;
    foreach ($entries as $entry) {
      $entry->encode($directory_offset + $i * $n);
      $i++;
    }
  }

  function parseHeader() {
    if (!empty($this->header)) {
      return;
    }

    $this->seek($this->tableOffset);

    $this->header = new Header($this);
    $this->header->parse();
  }

  function getFontType(){
    $class_parts = explode("\\", get_class($this));
    return $class_parts[1];
  }

  function parseTableEntries() {
    $this->parseHeader();

    if (!empty($this->directory)) {
      return;
    }

    if (empty($this->header->data["numTables"])) {
      return;
    }


    $type = $this->getFontType();
    $class = "FontLib\\$type\\TableDirectoryEntry";

    for ($i = 0; $i < $this->header->data["numTables"]; $i++) {
      /** @var TableDirectoryEntry $entry */
      $entry = new $class($this);
      $entry->parse();

      $this->directory[$entry->tag] = $entry;
    }
  }

  function normalizeFUnit($value, $base = 1000) {
    return round($value * ($base / $this->getData("head", "unitsPerEm")));
  }

  protected function readTable($tag) {
    $this->parseTableEntries();

    if (!self::$raw) {
      $name_canon = preg_replace("/[^a-z0-9]/", "", strtolower($tag));

      $class = "FontLib\\Table\\Type\\$name_canon";

      if (!isset($this->directory[$tag]) || !@class_exists($class)) {
        return;
      }
    }
    else {
      $class = "FontLib\\Table\\Table";
    }

    /** @var Table $table */
    $table = new $class($this->directory[$tag]);
    $table->parse();

    $this->data[$tag] = $table;
  }

  /**
   * @param $name
   *
   * @return Table
   */
  public function getTableObject($name) {
    return $this->data[$name];
  }

  public function setTableObject($name, Table $data) {
    $this->data[$name] = $data;
  }

  public function getData($name, $key = null) {
    $this->parseTableEntries();

    if (empty($this->data[$name])) {
      $this->readTable($name);
    }

    if (!isset($this->data[$name])) {
      return null;
    }

    if (!$key) {
      return $this->data[$name]->data;
    }
    else {
      return $this->data[$name]->data[$key];
    }
  }

  function addDirectoryEntry(DirectoryEntry $entry) {
    $this->directory[$entry->tag] = $entry;
  }

  function saveAdobeFontMetrics($file, $encoding = null) {
    $afm = new AdobeFontMetrics($this);
    $afm->write($file, $encoding);
  }

  /**
   * Get a specific name table string value from its ID
   *
   * @param int $nameID The name ID
   *
   * @return string|null
   */
  function getNameTableString($nameID) {
    /** @var nameRecord[] $records */
    $records = $this->getData("name", "records");

    if (!isset($records[$nameID])) {
      return null;
    }

    return $records[$nameID]->string;
  }

  /**
   * Get font copyright
   *
   * @return string|null
   */
  function getFontCopyright() {
    return $this->getNameTableString(name::NAME_COPYRIGHT);
  }

  /**
   * Get font name
   *
   * @return string|null
   */
  function getFontName() {
    return $this->getNameTableString(name::NAME_NAME);
  }

  /**
   * Get font subfamily
   *
   * @return string|null
   */
  function getFontSubfamily() {
    return $this->getNameTableString(name::NAME_SUBFAMILY);
  }

  /**
   * Get font subfamily ID
   *
   * @return string|null
   */
  function getFontSubfamilyID() {
    return $this->getNameTableString(name::NAME_SUBFAMILY_ID);
  }

  /**
   * Get font full name
   *
   * @return string|null
   */
  function getFontFullName() {
    return $this->getNameTableString(name::NAME_FULL_NAME);
  }

  /**
   * Get font version
   *
   * @return string|null
   */
  function getFontVersion() {
    return $this->getNameTableString(name::NAME_VERSION);
  }

  /**
   * Get font weight
   *
   * @return string|null
   */
  function getFontWeight() {
    return $this->getTableObject("OS/2")->data["usWeightClass"];
  }

  /**
   * Get font Postscript name
   *
   * @return string|null
   */
  function getFontPostscriptName() {
    return $this->getNameTableString(name::NAME_POSTSCRIPT_NAME);
  }

  function reduce() {
    $names_to_keep = array(
      name::NAME_COPYRIGHT,
      name::NAME_NAME,
      name::NAME_SUBFAMILY,
      name::NAME_SUBFAMILY_ID,
      name::NAME_FULL_NAME,
      name::NAME_VERSION,
      name::NAME_POSTSCRIPT_NAME,
    );

    foreach ($this->data["name"]->data["records"] as $id => $rec) {
      if (!in_array($id, $names_to_keep)) {
        unset($this->data["name"]->data["records"][$id]);
      }
    }
  }
}
