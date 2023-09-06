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

namespace Hoa\Zformat;

/**
 * Class \Hoa\Zformat\Parameter.
 *
 * Provide a class parameters support.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Parameter
{
    /**
     * Owner.
     *
     * @var string
     */
    protected $_owner            = null;

    /**
     * Parameters.
     *
     * @var array
     */
    protected $_parameters       = [];

    /**
     * Keywords.
     *
     * @var array
     */
    protected $_keywords         = [];

    /**
     * Constants values for zFormat.
     *
     * @var array
     */
    protected static $_constants = null;

    /**
     * Cache for zFormat.
     *
     * @var array
     */
    protected $_cache            = [];



    /**
     * Construct a new set of parameters.
     *
     * @param   mixed  $owner         Owner name or instance.
     * @param   array  $keywords      Keywords.
     * @param   array  $parameters    Parameters.
     * @throws  \Hoa\Zformat\Exception
     */
    public function __construct(
        $owner,
        array $keywords   = [],
        array $parameters = []
    ) {
        if (is_object($owner)) {
            if (!($owner instanceof Parameterizable)) {
                throw new Exception(
                    'Only parameterizable object can have parameter; ' .
                    '%s does implement \Hoa\Zformat\Parameterizable.',
                    0,
                    get_class($owner)
                );
            }

            $owner = get_class($owner);
        } else {
            $reflection = new \ReflectionClass($owner);

            if (false === $reflection->implementsInterface('\Hoa\Zformat\Parameterizable')) {
                throw new Exception(
                    'Only parameterizable object can have parameter; ' .
                    '%s does implement \Hoa\Zformat\Parameterizable.',
                    1,
                    $owner
                );
            }
        }

        $this->_owner = $owner;
        $this->setKeywords($keywords);
        $this->setDefault($parameters);

        return;
    }

    /**
     * Initialize constants.
     *
     * @return  void
     */
    public static function initializeConstants()
    {
        $c                = explode('…', date('d…j…N…w…z…W…m…n…Y…y…g…G…h…H…i…s…u…O…T…U'));
        self::$_constants = [
            'd' => $c[0],
            'j' => $c[1],
            'N' => $c[2],
            'w' => $c[3],
            'z' => $c[4],
            'W' => $c[5],
            'm' => $c[6],
            'n' => $c[7],
            'Y' => $c[8],
            'y' => $c[9],
            'g' => $c[10],
            'G' => $c[11],
            'h' => $c[12],
            'H' => $c[13],
            'i' => $c[14],
            's' => $c[15],
            'u' => $c[16],
            'O' => $c[17],
            'T' => $c[18],
            'U' => $c[19]
        ];

        return;
    }

    /**
     * Get constants.
     *
     * @return  array
     */
    public static function getConstants()
    {
        return self::$_constants;
    }

    /**
     * Set default parameters to a class.
     *
     * @param   array  $parameters    Parameters to set.
     * @return  void
     * @throws  \Hoa\Zformat\Exception
     */
    private function setDefault(array $parameters)
    {
        $this->_parameters = $parameters;

        return;
    }

    /**
     * Set parameters.
     *
     * @param   array   $parameters    Parameters.
     * @return  void
     */
    public function setParameters(array $parameters)
    {
        $this->resetCache();

        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }

        return;
    }

    /**
     * Get parameters.
     *
     * @return  array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Set a parameter.
     *
     * @param   string  $key      Key.
     * @param   mixed   $value    Value.
     * @return  mixed
     */
    public function setParameter($key, $value)
    {
        $this->resetCache();
        $old = null;

        if (true === array_key_exists($key, $this->_parameters)) {
            $old = $this->_parameters[$key];
        }

        $this->_parameters[$key] = $value;

        return $old;
    }

    /**
     * Get a parameter.
     *
     * @param   string  $parameter    Parameter.
     * @return  mixed
     */
    public function getParameter($parameter)
    {
        if (array_key_exists($parameter, $this->_parameters)) {
            return $this->_parameters[$parameter];
        }

        return null;
    }

    /**
     * Get a formatted parameter (i.e. zFormatted).
     *
     * @param   string  $parameter    Parameter.
     * @return  mixed
     */
    public function getFormattedParameter($parameter)
    {
        if (null === $value = $this->getParameter($parameter)) {
            return null;
        }

        return $this->zFormat($value);
    }

    /**
     * Check a branch exists.
     *
     * @param   string  $branch    Branch.
     * @return  bool
     */
    public function branchExists($branch)
    {
        $qBranch = preg_quote($branch);

        foreach ($this->getParameters() as $key => $value) {
            if (0 !== preg_match('#^' . $qBranch . '(.*)?#', $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Unlinearize a branch to an array.
     *
     * @param   string  $branch    Branch.
     * @return  array
     */
    public function unlinearizeBranch($branch)
    {
        $parameters = $this->getParameters();
        $out        = [];
        $lBranch    = strlen($branch);

        foreach ($parameters as $key => $value) {
            if ($branch !== substr($key, 0, $lBranch)) {
                continue;
            }

            $handle  = [];
            $explode = preg_split(
                '#((?<!\\\)\.)#',
                substr($key, $lBranch + 1),
                -1,
                PREG_SPLIT_NO_EMPTY
            );
            $end = count($explode) - 1;
            $i   = $end;

            while ($i >= 0) {
                $explode[$i] = str_replace('\\.', '.', $explode[$i]);

                if ($i != $end) {
                    $handle = [$explode[$i] => $handle];
                } else {
                    $handle = [$explode[$i] => $this->zFormat($value)];
                }

                --$i;
            }

            $out = array_merge_recursive($out, $handle);
        }

        return $out;
    }

    /**
     * Set keywords.
     *
     * @param   array   $keywords    Keywords.
     * @return  void
     * @throws  \Hoa\Zformat\Exception
     */
    public function setKeywords($keywords)
    {
        $this->resetCache();

        foreach ($keywords as $key => $value) {
            $this->setKeyword($key, $value);
        }

        return;
    }

    /**
     * Get keywords.
     *
     * @return  array
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * Set a keyword.
     *
     * @param   string  $key      Key.
     * @param   mixed   $value    Value.
     * @return  mixed
     */
    public function setKeyword($key, $value)
    {
        $this->resetCache();
        $old = null;

        if (true === array_key_exists($key, $this->_keywords)) {
            $old = $this->_keywords[$key];
        }

        $this->_keywords[$key] = $value;

        return $old;
    }

    /**
     * Get a keyword.
     *
     * @param   string  $keyword    Keyword.
     * @return  mixed
     */
    public function getKeyword($keyword)
    {
        if (true === array_key_exists($keyword, $this->_keywords)) {
            return $this->_keywords[$keyword];
        }

        return null;
    }

    /**
     * zFormat a string.
     * zFormat is inspired from the famous Zsh (please, take a look at
     * http://zsh.org), and specifically from ZStyle.
     *
     * ZFormat has the following pattern:
     *     (:subject[:format]:)
     *
     * where subject could be a:
     *   • keyword, i.e. a simple string: foo;
     *   • reference to an existing parameter, i.e. a simple string prefixed by
     *     a %: %bar;
     *   • constant, i.e. a combination of chars, first is prefixed by a _: _Ymd
     *     will given the current year, followed by the current month and
     *     finally the current day.
     *
     * and where the format is a combination of chars, that apply functions on
     * the subject:
     *   • h: to get the head of a path (equivalent to dirname);
     *   • t: to get the tail of a path (equivalent to basename);
     *   • r: to get the path without extension;
     *   • e: to get the extension;
     *   • l: to get the result in lowercase;
     *   • u: to get the result in uppercase;
     *   • U: to get the result with the first letter in uppercase (understand
     *        classname);
     *   • s/<foo>/<bar>/: to replace all matches <foo> by <bar> (the last / is
     *     optional, only if more options are given after);
     *   • s%<foo>%<bar>%: to replace the prefix <foo> by <bar> (the last % is
     *     also optional);
     *   • s#<foo>#<bar>#: to replace the suffix <foo> by <bar> (the last # is
     *     also optional).
     *
     * Known constants are:
     *   • d: day of the month, 2 digits with leading zeros;
     *   • j: day of the month without leading zeros;
     *   • N: ISO-8601 numeric representation of the day of the week;
     *   • w: numeric representation of the day of the week;
     *   • z: the day of the year (starting from 0);
     *   • W: ISO-8601 week number of year, weeks starting on Monday;
     *   • m: numeric representation of a month, with leading zeros;
     *   • n: numeric representation of a month, without leading zeros;
     *   • Y: a full numeric representation of a year, 4 digits;
     *   • y: a two digit representation of a year;
     *   • g: 12-hour format of an hour without leading zeros;
     *   • G: 24-hour format of an hour without leading zeros;
     *   • h: 12-hour format of an hour with leading zeros;
     *   • H: 24-hour format of an hour with leading zeros;
     *   • i: minutes with leading zeros;
     *   • s: seconds with leading zeros;
     *   • u: microseconds;
     *   • O: difference to Greenwich time (GMT) in hours;
     *   • T: timezone abbreviation;
     *   • U: seconds since the Unix Epoch (a timestamp).
     * They are very useful for dynamic cache paths for example.
     *
     * Examples:
     *   Let keywords $k and parameters $p:
     *     $k = [
     *         'foo'      => 'bar',
     *         'car'      => 'DeLoReAN',
     *         'power'    => 2.21,
     *         'answerTo' => 'life_universe_everything_else',
     *         'answerIs' => 42,
     *         'hello'    => 'wor.l.d'
     *     ];
     *     $p = [
     *         'plpl'        => '(:foo:U:)',
     *         'foo'         => 'ar(:%plpl:)',
     *         'favoriteCar' => 'A (:car:l:)!',
     *         'truth'       => 'To (:answerTo:ls/_/ /U:) is (:answerIs:).',
     *         'file'        => '/a/file/(:_Ymd:)/(:hello:trr:).(:power:e:)',
     *         'recursion'   => 'oof(:%foo:s#ar#az:)'
     *     ];
     *   Then, after applying the zFormat, we get:
     *     • plpl:        'Bar', put the first letter in uppercase;
     *     • foo:         'arBar', call the parameter plpl;
     *     • favoriteCar: 'A delorean!', all is in lowercase;
     *     • truth:       'To Life universe everything else is 42', all is in
     *                    lowercase, then replace underscores by spaces, and
     *                    finally put the first letter in uppercase; and no
     *                    transformation for 42;
     *     • file:        '/a/file/20090505/wor.21', get date constants, then
     *                    get the tail of the path and remove extension twice,
     *                    and add the extension of power;
     *     • recursion:   'oofarBaz', get 'arbar' first, and then, replace the
     *                    suffix 'ar' by 'az'.
     *
     * @param   string  $value    Parameter value.
     * @return  string
     * @throws  \Hoa\Zformat\Exception
     */
    public function zFormat($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (isset($this->_cache[$value])) {
            return $this->_cache[$value];
        }

        if (null === self::$_constants) {
            self::initializeConstants();
        }

        $self       = $this;
        $keywords   = $this->getKeywords();
        $parameters = $this->getParameters();

        return $this->_cache[$value] = preg_replace_callback(
            '#\(:(.*?):\)#',
            function ($match) use ($self, $value, &$keywords, &$parameters) {
                preg_match(
                    '#([^:]+)(?::(.*))?#',
                    $match[1],
                    $submatch
                );

                if (!isset($submatch[1])) {
                    return '';
                }

                $out  = null;
                $key  = $submatch[1];
                $word = substr($key, 1);

                // Call a parameter.
                if ('%' == $key[0]) {
                    if (false === array_key_exists($word, $parameters)) {
                        throw new Exception(
                            'Parameter %s is not found in parameters.',
                            0,
                            $word
                        );
                    }

                    $handle = $parameters[$word];
                    $out    = $self->zFormat($handle);
                }
                // Call a constant.
                elseif ('_' == $key[0]) {
                    $constants = Parameter::getConstants();

                    foreach (str_split($word) as $k => $v) {
                        if (!isset($constants[$v])) {
                            throw new Exception(
                                'Constant char %s is not supported in the ' .
                                'rule %s.',
                                1,
                                [$v, $value]
                            );
                        }

                        $out .= $constants[$v];
                    }
                }
                // Call a keyword.
                else {
                    if (false === array_key_exists($key, $keywords)) {
                        throw new Exception(
                            'Keyword %s is not found in the rule %s.',
                            2,
                            [$key, $value]
                        );
                    }

                    $out = $keywords[$key];
                }

                if (!isset($submatch[2])) {
                    return $out;
                }

                preg_match_all(
                    '#(h|t|r|e|l|u|U|s(/|%|\#)(.*?)(?<!\\\)\2(.*?)(?:(?<!\\\)\2|$))#',
                    $submatch[2],
                    $flags
                );

                if (empty($flags) || empty($flags[1])) {
                    throw new Exception(
                        'Unrecognized format pattern %s in the rule %s.',
                        3,
                        [$match[0], $value]
                    );
                }

                foreach ($flags[1] as $i => $flag) {
                    switch ($flag) {
                        case 'h':
                            $out = dirname($out);

                            break;

                        case 't':
                            $out = basename($out);

                            break;

                        case 'r':
                            if (false !== $position = strrpos($out, '.', 1)) {
                                $out = substr($out, 0, $position);
                            }

                            break;

                        case 'e':
                            if (false !== $position = strrpos($out, '.', 1)) {
                                $out = substr($out, $position + 1);
                            }

                            break;

                        case 'l':
                            $out = strtolower($out);

                            break;

                        case 'u':
                            $out = strtoupper($out);

                            break;

                        case 'U':
                            $handle = null;

                            foreach (explode('\\', $out) as $part) {
                                if (null === $handle) {
                                    $handle  = ucfirst($part);
                                } else {
                                    $handle .= '\\' . ucfirst($part);
                                }
                            }

                            $out = $handle;

                            break;

                        default:
                            if (!isset($flags[3]) && !isset($flags[4])) {
                                throw new Exception(
                                    'Unrecognized format pattern in the rule %s.',
                                    4,
                                    $value
                                );
                            }

                            $l = preg_quote($flags[3][$i], '#');
                            $r = $flags[4][$i];

                            switch ($flags[2][$i]) {
                                case '%':
                                    $l  = '^' . $l;

                                    break;

                                case '#':
                                    $l .= '$';

                                    break;
                            }

                            $out = preg_replace('#' . $l . '#', $r, $out);
                    }
                }

                return $out;
            },
            $value
        );
    }

    /**
     * Reset zFormat cache.
     *
     * @return  void
     */
    private function resetCache()
    {
        unset($this->_cache);
        $this->_cache = [];

        return;
    }
}
