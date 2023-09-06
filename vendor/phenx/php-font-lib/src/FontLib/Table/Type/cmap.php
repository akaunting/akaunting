<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib\Table\Type;
use FontLib\Table\Table;

/**
 * `cmap` font table.
 *
 * @package php-font-lib
 */
class cmap extends Table {
  private static $header_format = array(
    "version"         => self::uint16,
    "numberSubtables" => self::uint16,
  );

  private static $subtable_header_format = array(
    "platformID"         => self::uint16,
    "platformSpecificID" => self::uint16,
    "offset"             => self::uint32,
  );

  private static $subtable_v4_format = array(
    "length"        => self::uint16,
    "language"      => self::uint16,
    "segCountX2"    => self::uint16,
    "searchRange"   => self::uint16,
    "entrySelector" => self::uint16,
    "rangeShift"    => self::uint16,
  );

  private static $subtable_v12_format = array(
    "length"        => self::uint32,
    "language"      => self::uint32,
    "ngroups"    => self::uint32
  );

  protected function _parse() {
    $font = $this->getFont();

    $cmap_offset = $font->pos();

    $data = $font->unpack(self::$header_format);

    $subtables = array();
    for ($i = 0; $i < $data["numberSubtables"]; $i++) {
      $subtables[] = $font->unpack(self::$subtable_header_format);
    }

    $data["subtables"] = $subtables;

    foreach ($data["subtables"] as $i => &$subtable) {
      $font->seek($cmap_offset + $subtable["offset"]);

      $subtable["format"] = $font->readUInt16();

      // @todo Only CMAP version 4 and 12
      if (($subtable["format"] != 4) && ($subtable["format"] != 12)) {
        unset($data["subtables"][$i]);
        $data["numberSubtables"]--;
        continue;
      }

      if ($subtable["format"] == 12) {

        $font->readUInt16();

        $subtable += $font->unpack(self::$subtable_v12_format);

        $glyphIndexArray = array();
        $endCodes = array();
        $startCodes = array();

        for ($p = 0; $p < $subtable['ngroups']; $p++) {

          $startCode = $startCodes[] = $font->readUInt32();
          $endCode = $endCodes[] = $font->readUInt32();
          $startGlyphCode = $font->readUInt32();

          for ($c = $startCode; $c <= $endCode; $c++) {
            $glyphIndexArray[$c] = $startGlyphCode;
            $startGlyphCode++;
          }
        }

        $subtable += array(
          "startCode" => $startCodes,
          "endCode" => $endCodes,
          "glyphIndexArray" => $glyphIndexArray,
        );

      }
      else if ($subtable["format"] == 4) {

        $subtable += $font->unpack(self::$subtable_v4_format);

        $segCount             = $subtable["segCountX2"] / 2;
        $subtable["segCount"] = $segCount;

        $endCode = $font->readUInt16Many($segCount);

        $font->readUInt16(); // reservedPad

        $startCode = $font->readUInt16Many($segCount);
        $idDelta   = $font->readInt16Many($segCount);

        $ro_start      = $font->pos();
        $idRangeOffset = $font->readUInt16Many($segCount);

        $glyphIndexArray = array();
        for ($i = 0; $i < $segCount; $i++) {
          $c1 = $startCode[$i];
          $c2 = $endCode[$i];
          $d  = $idDelta[$i];
          $ro = $idRangeOffset[$i];

          if ($ro > 0) {
            $font->seek($subtable["offset"] + 2 * $i + $ro);
          }

          for ($c = $c1; $c <= $c2; $c++) {
            if ($ro == 0) {
              $gid = ($c + $d) & 0xFFFF;
            }
            else {
              $offset = ($c - $c1) * 2 + $ro;
              $offset = $ro_start + 2 * $i + $offset;

              $font->seek($offset);
              $gid = $font->readUInt16();

              if ($gid != 0) {
                $gid = ($gid + $d) & 0xFFFF;
              }
            }

            if ($gid > 0) {
              $glyphIndexArray[$c] = $gid;
            }
          }
        }

        $subtable += array(
          "endCode"         => $endCode,
          "startCode"       => $startCode,
          "idDelta"         => $idDelta,
          "idRangeOffset"   => $idRangeOffset,
          "glyphIndexArray" => $glyphIndexArray,
        );
      }
    }

    $this->data = $data;
  }

  function _encode() {
    $font = $this->getFont();

    $subset          = $font->getSubset();
    $glyphIndexArray = $font->getUnicodeCharMap();

    $newGlyphIndexArray = array();
    foreach ($glyphIndexArray as $code => $gid) {
      $new_gid = array_search($gid, $subset);
      if ($new_gid !== false) {
        $newGlyphIndexArray[$code] = $new_gid;
      }
    }

    ksort($newGlyphIndexArray); // Sort by char code

    $segments = array();

    $i        = -1;
    $prevCode = 0xFFFF;
    $prevGid  = 0xFFFF;

    foreach ($newGlyphIndexArray as $code => $gid) {
      if (
        $prevCode + 1 != $code ||
        $prevGid + 1 != $gid
      ) {
        $i++;
        $segments[$i] = array();
      }

      $segments[$i][] = array($code, $gid);

      $prevCode = $code;
      $prevGid  = $gid;
    }

    $segments[][] = array(0xFFFF, 0xFFFF);

    $startCode = array();
    $endCode   = array();
    $idDelta   = array();

    foreach ($segments as $codes) {
      $start = reset($codes);
      $end   = end($codes);

      $startCode[] = $start[0];
      $endCode[]   = $end[0];
      $idDelta[]   = $start[1] - $start[0];
    }

    $segCount      = count($startCode);
    $idRangeOffset = array_fill(0, $segCount, 0);

    $searchRange   = 1;
    $entrySelector = 0;
    while ($searchRange * 2 <= $segCount) {
      $searchRange *= 2;
      $entrySelector++;
    }
    $searchRange *= 2;
    $rangeShift = $segCount * 2 - $searchRange;

    $subtables = array(
      array(
        // header
        "platformID"         => 3, // Unicode
        "platformSpecificID" => 1,
        "offset"             => null,

        // subtable
        "format"             => 4,
        "length"             => null,
        "language"           => 0,
        "segCount"           => $segCount,
        "segCountX2"         => $segCount * 2,
        "searchRange"        => $searchRange,
        "entrySelector"      => $entrySelector,
        "rangeShift"         => $rangeShift,
        "startCode"          => $startCode,
        "endCode"            => $endCode,
        "idDelta"            => $idDelta,
        "idRangeOffset"      => $idRangeOffset,
        "glyphIndexArray"    => $newGlyphIndexArray,
      )
    );

    $data = array(
      "version"         => 0,
      "numberSubtables" => count($subtables),
      "subtables"       => $subtables,
    );

    $length = $font->pack(self::$header_format, $data);

    $subtable_headers_size   = $data["numberSubtables"] * 8; // size of self::$subtable_header_format
    $subtable_headers_offset = $font->pos();

    $length += $font->write(str_repeat("\0", $subtable_headers_size), $subtable_headers_size);

    // write subtables data
    foreach ($data["subtables"] as $i => $subtable) {
      $length_before                   = $length;
      $data["subtables"][$i]["offset"] = $length;

      $length += $font->writeUInt16($subtable["format"]);

      $before_subheader = $font->pos();
      $length += $font->pack(self::$subtable_v4_format, $subtable);

      $segCount = $subtable["segCount"];
      $length += $font->w(array(self::uint16, $segCount), $subtable["endCode"]);
      $length += $font->writeUInt16(0); // reservedPad
      $length += $font->w(array(self::uint16, $segCount), $subtable["startCode"]);
      $length += $font->w(array(self::int16, $segCount), $subtable["idDelta"]);
      $length += $font->w(array(self::uint16, $segCount), $subtable["idRangeOffset"]);
      $length += $font->w(array(self::uint16, $segCount), array_values($subtable["glyphIndexArray"]));

      $after_subtable = $font->pos();

      $subtable["length"] = $length - $length_before;
      $font->seek($before_subheader);
      $length += $font->pack(self::$subtable_v4_format, $subtable);

      $font->seek($after_subtable);
    }

    // write subtables headers
    $font->seek($subtable_headers_offset);
    foreach ($data["subtables"] as $subtable) {
      $font->pack(self::$subtable_header_format, $subtable);
    }

    return $length;
  }
}
