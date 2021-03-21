<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Ustring;

use Hoa\Consistency;

/**
 * Class \Hoa\Ustring.
 *
 * This class represents a UTF-8 string.
 * Please, see:
 *     • http://www.ietf.org/rfc/rfc3454.txt;
 *     • http://unicode.org/reports/tr9/;
 *     • http://www.unicode.org/Public/6.0.0/ucd/UnicodeData.txt.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Ustring implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Left-To-Right.
     *
     * @const int
     */
    const LTR              = 0;

    /**
     * Right-To-Left.
     *
     * @const int
     */
    const RTL              = 1;

    /**
     * ZERO WIDTH NON-BREAKING SPACE (ZWNPBSP, aka byte-order mark, BOM).
     *
     * @const int
     */
    const BOM              = 0xfeff;

    /**
     * LEFT-TO-RIGHT MARK.
     *
     * @const int
     */
    const LRM              = 0x200e;

    /**
     * RIGHT-TO-LEFT MARK.
     *
     * @const int
     */
    const RLM              = 0x200f;

    /**
     * LEFT-TO-RIGHT EMBEDDING.
     *
     * @const int
     */
    const LRE              = 0x202a;

    /**
     * RIGHT-TO-LEFT EMBEDDING.
     *
     * @const int
     */
    const RLE              = 0x202b;

    /**
     * POP DIRECTIONAL FORMATTING.
     *
     * @const int
     */
    const PDF              = 0x202c;

    /**
     * LEFT-TO-RIGHT OVERRIDE.
     *
     * @const int
     */
    const LRO              = 0x202d;

    /**
     * RIGHT-TO-LEFT OVERRIDE.
     *
     * @const int
     */
    const RLO              = 0x202e;

    /**
     * Represent the beginning of the string.
     *
     * @const int
     */
    const BEGINNING        = 1;

    /**
     * Represent the end of the string.
     *
     * @const int
     */
    const END              = 2;

    /**
     * Split: non-empty pieces is returned.
     *
     * @const int
     */
    const WITHOUT_EMPTY    = PREG_SPLIT_NO_EMPTY;

    /**
     * Split: parenthesized expression in the delimiter pattern will be captured
     * and returned.
     *
     * @const int
     */
    const WITH_DELIMITERS  = PREG_SPLIT_DELIM_CAPTURE;

    /**
     * Split: offsets of captures will be returned.
     *
     * @const int
     */
    const WITH_OFFSET      = 260; //   PREG_OFFSET_CAPTURE
                                  // | PREG_SPLIT_OFFSET_CAPTURE

    /**
     * Group results by patterns.
     *
     * @const int
     */
    const GROUP_BY_PATTERN = PREG_PATTERN_ORDER;

    /**
     * Group results by tuple (set of patterns).
     *
     * @const int
     */
    const GROUP_BY_TUPLE   = PREG_SET_ORDER;

    /**
     * Current string.
     *
     * @var string
     */
    protected $_string          = null;

    /**
     * Direction. Please see self::LTR and self::RTL constants.
     *
     * @var int
     */
    protected $_direction       = null;

    /**
     * Collator.
     *
     * @var \Collator
     */
    protected static $_collator = null;



    /**
     * Construct a UTF-8 string.
     *
     * @param   string  $string    String.
     */
    public function __construct($string = null)
    {
        if (null !== $string) {
            $this->append($string);
        }

        return;
    }

    /**
     * Check if ext/mbstring is available.
     *
     * @return  bool
     */
    public static function checkMbString()
    {
        return function_exists('mb_substr');
    }

    /**
     * Check if ext/iconv is available.
     *
     * @return  bool
     */
    public static function checkIconv()
    {
        return function_exists('iconv');
    }

    /**
     * Append a substring to the current string, i.e. add to the end.
     *
     * @param   string  $substring    Substring to append.
     * @return  \Hoa\Ustring
     */
    public function append($substring)
    {
        $this->_string .= $substring;

        return $this;
    }

    /**
     * Prepend a substring to the current string, i.e. add to the start.
     *
     * @param   string  $substring    Substring to append.
     * @return  \Hoa\Ustring
     */
    public function prepend($substring)
    {
        $this->_string = $substring . $this->_string;

        return $this;
    }

    /**
     * Pad the current string to a certain length with another piece, aka piece.
     *
     * @param   int     $length    Length.
     * @param   string  $piece     Piece.
     * @param   int     $side      Whether we append at the end or the beginning
     *                             of the current string.
     * @return  \Hoa\Ustring
     */
    public function pad($length, $piece, $side = self::END)
    {
        $difference = $length - $this->count();

        if (0 >= $difference) {
            return $this;
        }

        $handle = null;

        for ($i = $difference / mb_strlen($piece) - 1; $i >= 0; --$i) {
            $handle .= $piece;
        }

        $handle .= mb_substr($piece, 0, $difference - mb_strlen($handle));

        return
            static::END === $side
                ? $this->append($handle)
                : $this->prepend($handle);
    }

    /**
     * Make a comparison with a string.
     * Return < 0 if current string is less than $string, > 0 if greater and 0
     * if equal.
     *
     * @param   mixed  $string    String.
     * @return  int
     */
    public function compare($string)
    {
        if (null === $collator = static::getCollator()) {
            return strcmp($this->_string, (string) $string);
        }

        return $collator->compare($this->_string, $string);
    }

    /**
     * Get collator.
     *
     * @return  \Collator
     */
    public static function getCollator()
    {
        if (false === class_exists('Collator')) {
            return null;
        }

        if (null === static::$_collator) {
            static::$_collator = new \Collator(setlocale(LC_COLLATE, null));
        }

        return static::$_collator;
    }

    /**
     * Ensure that the pattern is safe for Unicode: add the “u” option.
     *
     * @param   string  $pattern    Pattern.
     * @return  string
     */
    public static function safePattern($pattern)
    {
        $delimiter = mb_substr($pattern, 0, 1);
        $options   = mb_substr(
            mb_strrchr($pattern, $delimiter, false),
            mb_strlen($delimiter)
        );

        if (false === strpos($options, 'u')) {
            $pattern .= 'u';
        }

        return $pattern;
    }

    /**
     * Perform a regular expression (PCRE) match.
     *
     * @param   string  $pattern    Pattern.
     * @param   array   $matches    Matches.
     * @param   int     $flags      Please, see constants self::WITH_OFFSET,
     *                              self::GROUP_BY_PATTERN and
     *                              self::GROUP_BY_TUPLE.
     * @param   int     $offset     Alternate place from which to start the
     *                              search.
     * @param   bool    $global     Whether the match is global or not.
     * @return  int
     */
    public function match(
        $pattern,
        &$matches = null,
        $flags    = 0,
        $offset   = 0,
        $global   = false
    ) {
        $pattern = static::safePattern($pattern);

        if (0 === $flags) {
            if (true === $global) {
                $flags = static::GROUP_BY_PATTERN;
            }
        } else {
            $flags &= ~PREG_SPLIT_OFFSET_CAPTURE;
        }


        $offset = strlen(mb_substr($this->_string, 0, $offset));

        if (true === $global) {
            return preg_match_all(
                $pattern,
                $this->_string,
                $matches,
                $flags,
                $offset
            );
        }

        return preg_match($pattern, $this->_string, $matches, $flags, $offset);
    }

    /**
     * Perform a regular expression (PCRE) search and replace.
     *
     * @param   mixed   $pattern        Pattern(s).
     * @param   mixed   $replacement    Replacement(s) (please, see
     *                                  preg_replace() documentation).
     * @param   int     $limit          Maximum of replacements. -1 for unbound.
     * @return  \Hoa\Ustring
     */
    public function replace($pattern, $replacement, $limit = -1)
    {
        $pattern = static::safePattern($pattern);

        if (false === is_callable($replacement)) {
            $this->_string = preg_replace(
                $pattern,
                $replacement,
                $this->_string,
                $limit
            );
        } else {
            $this->_string = preg_replace_callback(
                $pattern,
                $replacement,
                $this->_string,
                $limit
            );
        }

        return $this;
    }

    /**
     * Split the current string according to a given pattern (PCRE).
     *
     * @param   string  $pattern    Pattern (as a regular expression).
     * @param   int     $limit      Maximum of split. -1 for unbound.
     * @param   int     $flags      Please, see constants self::WITHOUT_EMPTY,
     *                              self::WITH_DELIMITERS, self::WITH_OFFSET.
     * @return  array
     */
    public function split(
        $pattern,
        $limit = -1,
        $flags = self::WITHOUT_EMPTY
    ) {
        return preg_split(
            static::safePattern($pattern),
            $this->_string,
            $limit,
            $flags
        );
    }

    /**
     * Iterator over chars.
     *
     * @return  \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator(preg_split('#(?<!^)(?!$)#u', $this->_string));
    }

    /**
     * Perform a lowercase folding on the current string.
     *
     * @return  \Hoa\Ustring
     */
    public function toLowerCase()
    {
        $this->_string = mb_strtolower($this->_string);

        return $this;
    }

    /**
     * Perform an uppercase folding on the current string.
     *
     * @return  \Hoa\Ustring
     */
    public function toUpperCase()
    {
        $this->_string = mb_strtoupper($this->_string);

        return $this;
    }

    /**
     * Transform a UTF-8 string into an ASCII one.
     * First, try with a transliterator. If not available, will fallback to a
     * normalizer. If not available, will try something homemade.
     *
     * @param   bool  $try    Try something if \Normalizer is not present.
     * @return  \Hoa\Ustring
     * @throws  \Hoa\Ustring\Exception
     */
    public function toAscii($try = false)
    {
        if (0 === preg_match('#[\x80-\xff]#', $this->_string)) {
            return $this;
        }

        $string  = $this->_string;
        $transId =
            'Any-Latin; ' .
            '[\p{S}] Name; ' .
            'Latin-ASCII';

        if (null !== $transliterator = static::getTransliterator($transId)) {
            $this->_string = preg_replace_callback(
                '#\\\N\{([A-Z ]+)\}#u',
                function (array $matches) {
                    return '(' . strtolower($matches[1]) . ')';
                },
                $transliterator->transliterate($string)
            );

            return $this;
        }

        if (false === class_exists('Normalizer')) {
            if (false === $try) {
                throw new Exception(
                    '%s needs the class Normalizer to work properly, ' .
                    'or you can force a try by using %1$s(true).',
                    0,
                    __METHOD__
                );
            }

            $string        = static::transcode($string, 'UTF-8', 'ASCII//IGNORE//TRANSLIT');
            $this->_string = preg_replace('#(?:[\'"`^](\w))#u', '\1', $string);

            return $this;
        }

        $string        = \Normalizer::normalize($string, \Normalizer::NFKD);
        $string        = preg_replace('#\p{Mn}+#u', '', $string);
        $this->_string = static::transcode($string, 'UTF-8', 'ASCII//IGNORE//TRANSLIT');

        return $this;
    }

    /**
     * Transliterate the string into another.
     * See self::getTransliterator for more information.
     *
     * @param   string  $identifier    Identifier.
     * @param   int     $start         Start.
     * @param   int     $end           End.
     * @return  \Hoa\Ustring
     * @throws  \Hoa\Ustring\Exception
     */
    public function transliterate($identifier, $start = 0, $end = null)
    {
        if (null === $transliterator = static::getTransliterator($identifier)) {
            throw new Exception(
                '%s needs the class Transliterator to work properly.',
                1,
                __METHOD__
            );
        }

        $this->_string = $transliterator->transliterate($this->_string, $start, $end);

        return $this;
    }

    /**
     * Get transliterator.
     * See http://userguide.icu-project.org/transforms/general for $identifier.
     *
     * @param   string  $identifier    Identifier.
     * @return  \Transliterator
     */
    public static function getTransliterator($identifier)
    {
        if (false === class_exists('Transliterator')) {
            return null;
        }

        return \Transliterator::create($identifier);
    }

    /**
     * Strip characters (default \s) of the current string.
     *
     * @param   string  $regex    Characters to remove.
     * @param   int     $side     Whether we trim the beginning, the end or both
     *                            sides, of the current string.
     * @return  \Hoa\Ustring
     */
    public function trim($regex = '\s', $side = 3 /* static::BEGINNING | static::END */)
    {
        $regex  = '(?:' . $regex . ')+';
        $handle = null;

        if (0 !== ($side & static::BEGINNING)) {
            $handle .= '(^' . $regex . ')';
        }

        if (0 !== ($side & static::END)) {
            if (null !== $handle) {
                $handle .= '|';
            }

            $handle .= '(' . $regex . '$)';
        }

        $this->_string    = preg_replace('#' . $handle . '#u', '', $this->_string);
        $this->_direction = null;

        return $this;
    }

    /**
     * Compute offset (negative, unbound etc.).
     *
     * @param   int        $offset    Offset.
     * @return  int
     */
    protected function computeOffset($offset)
    {
        $length = mb_strlen($this->_string);

        if (0 > $offset) {
            $offset = -$offset % $length;

            if (0 !== $offset) {
                $offset = $length - $offset;
            }
        } elseif ($offset >= $length) {
            $offset %= $length;
        }

        return $offset;
    }

    /**
     * Get a specific chars of the current string.
     *
     * @param   int     $offset    Offset (can be negative and unbound).
     * @return  string
     */
    public function offsetGet($offset)
    {
        return mb_substr($this->_string, $this->computeOffset($offset), 1);
    }

    /**
     * Set a specific character of the current string.
     *
     * @param   int     $offset    Offset (can be negative and unbound).
     * @param   string  $value     Value.
     * @return  \Hoa\Ustring
     */
    public function offsetSet($offset, $value)
    {
        $head   = null;
        $offset = $this->computeOffset($offset);

        if (0 < $offset) {
            $head = mb_substr($this->_string, 0, $offset);
        }

        $tail             = mb_substr($this->_string, $offset + 1);
        $this->_string    = $head . $value . $tail;
        $this->_direction = null;

        return $this;
    }

    /**
     * Delete a specific character of the current string.
     *
     * @param   int     $offset    Offset (can be negative and unbound).
     * @return  string
     */
    public function offsetUnset($offset)
    {
        return $this->offsetSet($offset, null);
    }

    /**
     * Check if a specific offset exists.
     *
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return true;
    }

    /**
     * Reduce the strings.
     *
     * @param   int  $start     Position of first character.
     * @param   int  $length    Maximum number of characters.
     * @return  \Hoa\Ustring
     */
    public function reduce($start, $length = null)
    {
        $this->_string = mb_substr($this->_string, $start, $length);

        return $this;
    }

    /**
     * Count number of characters of the current string.
     *
     * @return  int
     */
    public function count()
    {
        return mb_strlen($this->_string);
    }

    /**
     * Get byte (not character) at a specific offset.
     *
     * @param   int     $offset    Offset (can be negative and unbound).
     * @return  string
     */
    public function getByteAt($offset)
    {
        $length = strlen($this->_string);

        if (0 > $offset) {
            $offset = -$offset % $length;

            if (0 !== $offset) {
                $offset = $length - $offset;
            }
        } elseif ($offset >= $length) {
            $offset %= $length;
        }

        return $this->_string[$offset];
    }

    /**
     * Count number of bytes (not characters) of the current string.
     *
     * @return  int
     */
    public function getBytesLength()
    {
        return strlen($this->_string);
    }

    /**
     * Get the width of the current string.
     * Useful when printing the string in monotype (some character need more
     * than one column to be printed).
     *
     * @return  int
     */
    public function getWidth()
    {
        return mb_strwidth($this->_string);
    }

    /**
     * Get direction of the current string.
     * Please, see the self::LTR and self::RTL constants.
     * It does not yet support embedding directions.
     *
     * @return  int
     */
    public function getDirection()
    {
        if (null === $this->_direction) {
            if (null === $this->_string) {
                $this->_direction = static::LTR;
            } else {
                $this->_direction = static::getCharDirection(
                    mb_substr($this->_string, 0, 1)
                );
            }
        }

        return $this->_direction;
    }

    /**
     * Get character of a specific character.
     * Please, see the self::LTR and self::RTL constants.
     *
     * @param   string  $char    Character.
     * @return  int
     */
    public static function getCharDirection($char)
    {
        $c = static::toCode($char);

        if (!(0x5be <= $c && 0x10b7f >= $c)) {
            return static::LTR;
        }

        if (0x85e >= $c) {
            if (0x5be === $c ||
                0x5c0 === $c ||
                0x5c3 === $c ||
                0x5c6 === $c ||
                (0x5d0 <= $c && 0x5ea >= $c) ||
                (0x5f0 <= $c && 0x5f4 >= $c) ||
                0x608 === $c ||
                0x60b === $c ||
                0x60d === $c ||
                0x61b === $c ||
                (0x61e <= $c && 0x64a >= $c) ||
                (0x66d <= $c && 0x66f >= $c) ||
                (0x671 <= $c && 0x6d5 >= $c) ||
                (0x6e5 <= $c && 0x6e6 >= $c) ||
                (0x6ee <= $c && 0x6ef >= $c) ||
                (0x6fa <= $c && 0x70d >= $c) ||
                0x710 === $c ||
                (0x712 <= $c && 0x72f >= $c) ||
                (0x74d <= $c && 0x7a5 >= $c) ||
                0x7b1 === $c ||
                (0x7c0 <= $c && 0x7ea >= $c) ||
                (0x7f4 <= $c && 0x7f5 >= $c) ||
                0x7fa === $c ||
                (0x800 <= $c && 0x815 >= $c) ||
                0x81a === $c ||
                0x824 === $c ||
                0x828 === $c ||
                (0x830 <= $c && 0x83e >= $c) ||
                (0x840 <= $c && 0x858 >= $c) ||
                0x85e === $c) {
                return static::RTL;
            }
        } elseif (0x200f === $c) {
            return static::RTL;
        } elseif (0xfb1d <= $c) {
            if (0xfb1d === $c ||
                (0xfb1f <= $c && 0xfb28 >= $c) ||
                (0xfb2a <= $c && 0xfb36 >= $c) ||
                (0xfb38 <= $c && 0xfb3c >= $c) ||
                0xfb3e === $c ||
                (0xfb40 <= $c && 0xfb41 >= $c) ||
                (0xfb43 <= $c && 0xfb44 >= $c) ||
                (0xfb46 <= $c && 0xfbc1 >= $c) ||
                (0xfbd3 <= $c && 0xfd3d >= $c) ||
                (0xfd50 <= $c && 0xfd8f >= $c) ||
                (0xfd92 <= $c && 0xfdc7 >= $c) ||
                (0xfdf0 <= $c && 0xfdfc >= $c) ||
                (0xfe70 <= $c && 0xfe74 >= $c) ||
                (0xfe76 <= $c && 0xfefc >= $c) ||
                (0x10800 <= $c && 0x10805 >= $c) ||
                0x10808 === $c ||
                (0x1080a <= $c && 0x10835 >= $c) ||
                (0x10837 <= $c && 0x10838 >= $c) ||
                0x1083c === $c ||
                (0x1083f <= $c && 0x10855 >= $c) ||
                (0x10857 <= $c && 0x1085f >= $c) ||
                (0x10900 <= $c && 0x1091b >= $c) ||
                (0x10920 <= $c && 0x10939 >= $c) ||
                0x1093f === $c ||
                0x10a00 === $c ||
                (0x10a10 <= $c && 0x10a13 >= $c) ||
                (0x10a15 <= $c && 0x10a17 >= $c) ||
                (0x10a19 <= $c && 0x10a33 >= $c) ||
                (0x10a40 <= $c && 0x10a47 >= $c) ||
                (0x10a50 <= $c && 0x10a58 >= $c) ||
                (0x10a60 <= $c && 0x10a7f >= $c) ||
                (0x10b00 <= $c && 0x10b35 >= $c) ||
                (0x10b40 <= $c && 0x10b55 >= $c) ||
                (0x10b58 <= $c && 0x10b72 >= $c) ||
                (0x10b78 <= $c && 0x10b7f >= $c)) {
                return static::RTL;
            }
        }

        return static::LTR;
    }

    /**
     * Get the number of column positions of a wide-character.
     *
     * This is a PHP implementation of wcwidth() and wcswidth() (defined in IEEE
     * Std 1002.1-2001) for Unicode, by Markus Kuhn. Please, see
     * http://www.cl.cam.ac.uk/~mgk25/ucs/wcwidth.c.
     *
     * The wcwidth(wc) function shall either return 0 (if wc is a null
     * wide-character code), or return the number of column positions to be
     * occupied by the wide-character code wc, or return -1 (if wc does not
     * correspond to a printable wide-character code).
     *
     * @param   string  $char    Character.
     * @return  int
     */
    public static function getCharWidth($char)
    {
        $char = (string) $char;
        $c    = static::toCode($char);

        // Test for 8-bit control characters.
        if (0x0 === $c) {
            return 0;
        }

        if (0x20 > $c || (0x7f <= $c && $c < 0xa0)) {
            return -1;
        }

        // Non-spacing characters.
        if (0xad !== $c &&
            0    !== preg_match('#^[\p{Mn}\p{Me}\p{Cf}\x{1160}-\x{11ff}\x{200b}]#u', $char)) {
            return 0;
        }

        // If we arrive here, $c is not a combining C0/C1 control character.
        return 1 +
            (0x1100 <= $c &&
                (0x115f >= $c ||                        // Hangul Jamo init. consonants
                 0x2329 === $c || 0x232a === $c ||
                     (0x2e80 <= $c && 0xa4cf >= $c &&
                      0x303f !== $c) ||                 // CJK…Yi
                     (0xac00  <= $c && 0xd7a3 >= $c) || // Hangul Syllables
                     (0xf900  <= $c && 0xfaff >= $c) || // CJK Compatibility Ideographs
                     (0xfe10  <= $c && 0xfe19 >= $c) || // Vertical forms
                     (0xfe30  <= $c && 0xfe6f >= $c) || // CJK Compatibility Forms
                     (0xff00  <= $c && 0xff60 >= $c) || // Fullwidth Forms
                     (0xffe0  <= $c && 0xffe6 >= $c) ||
                     (0x20000 <= $c && 0x2fffd >= $c) ||
                     (0x30000 <= $c && 0x3fffd >= $c)));
    }

    /**
     * Check whether the character is printable or not.
     *
     * @param   string  $char    Character.
     * @return  bool
     */
    public static function isCharPrintable($char)
    {
        return 1 <= static::getCharWidth($char);
    }

    /**
     * Get a UTF-8 character from its decimal code representation.
     *
     * @param   int  $code    Code.
     * @return  string
     */
    public static function fromCode($code)
    {
        return mb_convert_encoding(
            '&#x' . dechex($code) . ';',
            'UTF-8',
            'HTML-ENTITIES'
        );
    }

    /**
     * Get a decimal code representation of a specific character.
     *
     * @param   string  $char    Character.
     * @return  int
     */
    public static function toCode($char)
    {
        $char  = (string) $char;
        $code  = ord($char[0]);
        $bytes = 1;

        if (!($code & 0x80)) { // 0xxxxxxx
            return $code;
        }

        if (($code & 0xe0) === 0xc0) { // 110xxxxx
            $bytes = 2;
            $code  = $code & ~0xc0;
        } elseif (($code & 0xf0) == 0xe0) { // 1110xxxx
            $bytes = 3;
            $code  = $code & ~0xe0;
        } elseif (($code & 0xf8) === 0xf0) { // 11110xxx
            $bytes = 4;
            $code  = $code & ~0xf0;
        }

        for ($i = 2; $i <= $bytes; $i++) { // 10xxxxxx
            $code = ($code << 6) + (ord($char[$i - 1]) & ~0x80);
        }

        return $code;
    }

    /**
     * Get a binary representation of a specific character.
     *
     * @param   string  $char    Character.
     * @return  string
     */
    public static function toBinaryCode($char)
    {
        $char = (string) $char;
        $out  = null;

        for ($i = 0, $max = strlen($char); $i < $max; ++$i) {
            $out .= vsprintf('%08b', ord($char[$i]));
        }

        return $out;
    }

    /**
     * Transcode.
     *
     * @param   string  $string    String.
     * @param   string  $from      Original encoding.
     * @param   string  $to        Final encoding.
     * @return  string
     * @throws  \Hoa\Ustring\Exception
     */
    public static function transcode($string, $from, $to = 'UTF-8')
    {
        if (false === static::checkIconv()) {
            throw new Exception(
                '%s needs the iconv extension.',
                2,
                __CLASS__
            );
        }

        return iconv($from, $to, $string);
    }

    /**
     * Check if a string is encoded in UTF-8.
     *
     * @param   string  $string    String.
     * @return  bool
     */
    public static function isUtf8($string)
    {
        return (bool) preg_match('##u', $string);
    }

    /**
     * Copy current object string
     *
     * @return \Hoa\Ustring
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Transform the object as a string.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->_string;
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Ustring\Ustring');

if (false === Ustring::checkMbString()) {
    throw new Exception(
        '%s needs the mbstring extension.',
        0,
        __NAMESPACE__ . '\Ustring'
    );
}
