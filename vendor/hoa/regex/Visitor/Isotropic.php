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

namespace Hoa\Regex\Visitor;

use Hoa\Math;
use Hoa\Regex;
use Hoa\Ustring;
use Hoa\Visitor;

/**
 * Class \Hoa\Regex\Visitor\Isotropic.
 *
 * Isotropic walk on the AST to generate a data.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Isotropic implements Visitor\Visit
{
    /**
     * Numeric-sampler.
     *
     * @var \Hoa\Math\Sampler
     */
    protected $_sampler = null;



    /**
     * Initialize numeric-sampler.
     *
     * @param   \Hoa\Math\Sampler  $sampler    Numeric-sampler.
     */
    public function __construct(Math\Sampler $sampler)
    {
        $this->_sampler = $sampler;

        return;
    }

    /**
     * Visit an element.
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     * @throws  \Hoa\Regex\Exception
     */
    public function visit(
        Visitor\Element $element,
        &$handle = null,
        $eldnah = null
    ) {
        switch ($element->getId()) {
            case '#expression':
            case '#capturing':
            case '#noncapturing':
            case '#namedcapturing':
                return $element->getChild(0)->accept($this, $handle, $eldnah);

            case '#alternation':
            case '#class':
                return $element->getChild($this->_sampler->getInteger(
                    0,
                    $element->getChildrenNumber() - 1
                ))->accept($this, $handle, $eldnah);

            case '#concatenation':
                $out = null;

                foreach ($element->getChildren() as $child) {
                    $out .= $child->accept($this, $handle, $eldnah);
                }

                return $out;

            case '#quantification':
                $out = null;
                $xy  = $element->getChild(1)->getValueValue();
                $x   = 0;
                $y   = 0;

                switch ($element->getChild(1)->getValueToken()) {
                    case 'zero_or_one':
                        $y = 1;

                        break;

                    case 'zero_or_more':
                        $y = mt_rand(5, 8); // why not?

                        break;

                    case 'one_or_more':
                        $x = 1;
                        $y = mt_rand(5, 8); // why not?

                        break;

                    case 'exactly_n':
                        $x = $y = (int) substr($xy, 1, -1);

                        break;

                    case 'n_to_m':
                        $xy = explode(',', substr($xy, 1, -1));
                        $x  = (int) trim($xy[0]);
                        $y  = (int) trim($xy[1]);

                        break;

                    case 'n_or_more':
                        $xy = explode(',', substr($xy, 1, -1));
                        $x  = (int) trim($xy[0]);
                        $y  = mt_rand($x + 5, $x + 8); // why not?

                        break;
                }

                for (
                    $i = 0, $max = $this->_sampler->getInteger($x, $y);
                    $i < $max;
                    ++$i
                ) {
                    $out .= $element->getChild(0)->accept(
                        $this,
                        $handle,
                        $eldnah
                    );
                }

                return $out;

            case '#negativeclass':
                $c = [];

                foreach ($element->getChildren() as $child) {
                    $c[Ustring::toCode(
                        $child->accept($this, $handle, $eldnah)
                    )] = true;
                }

                do {
                    // all printable ASCII.
                    $i = $this->_sampler->getInteger(32, 126);
                } while (isset($c[$i]));

                return Ustring::fromCode($i);

            case '#range':
                $out   = null;
                $left  = $element->getChild(0)->accept($this, $handle, $eldnah);
                $right = $element->getChild(1)->accept($this, $handle, $eldnah);

                return
                    Ustring::fromCode(
                        $this->_sampler->getInteger(
                            Ustring::toCode($left),
                            Ustring::toCode($right)
                        )
                    );

            case 'token':
                $value = $element->getValueValue();

                switch ($element->getValueToken()) {
                    case 'character':
                        $value = ltrim($value, '\\');

                        switch ($value) {
                            case 'a':
                                return "\a";

                            case 'e':
                                return "\e";

                            case 'f':
                                return "\f";

                            case 'n':
                                return "\n";

                            case 'r':
                                return "\r";

                            case 't':
                                return "\t";

                            default:
                                return
                                    Ustring::fromCode(
                                        intval(
                                            substr($value, 1)
                                        )
                                    );
                        }

                        break;

                    case 'dynamic_character':
                        $value = ltrim($value, '\\');

                        switch ($value[0]) {
                            case 'x':
                                $value = trim($value, 'x{}');

                                return Ustring::fromCode(
                                    hexdec($value)
                                );

                            default:
                                return Ustring::fromCode(octdec($value));
                        }

                        break;

                    case 'character_type':
                        $value = ltrim($value, '\\');

                        if ('s' === $value) {
                            $value = $this->_sampler->getInteger(0, 1) ? 'h' : 'v';
                        }

                        switch ($value) {
                            case 'C':
                                return $this->_sampler->getInteger(0, 127);

                            case 'd':
                                return $this->_sampler->getInteger(0, 9);

                            case 'h':
                                $h = [
                                    Ustring::fromCode(0x0009),
                                    Ustring::fromCode(0x0020),
                                    Ustring::fromCode(0x00a0)
                                ];

                                return $h[$this->_sampler->getInteger(0, count($h) -1)];

                            case 'v':
                                $v = [
                                    Ustring::fromCode(0x000a),
                                    Ustring::fromCode(0x000b),
                                    Ustring::fromCode(0x000c),
                                    Ustring::fromCode(0x000d)
                                ];

                                return $v[$this->_sampler->getInteger(0, count($v) -1)];

                            case 'w':
                                $w  = array_merge(
                                    range(0x41, 0x5a),
                                    range(0x61, 0x7a),
                                    [0x5f]
                                );

                                return Ustring::fromCode($w[$this->_sampler->getInteger(0, count($w) - 1)]);

                            default:
                                return '?';
                        }

                        break;

                    case 'literal':
                        if ('.' === $value) {
                            $w  = array_merge(
                                range(0x41, 0x5a),
                                range(0x61, 0x7a),
                                [0x5f]
                            );

                            return Ustring::fromCode($w[$this->_sampler->getInteger(0, count($w) - 1)]);
                        }

                        return
                            str_replace(
                                '\\\\',
                                '\\',
                                preg_replace(
                                    '#\\\(?!\\\)#',
                                    '',
                                    $value
                                )
                            );
                }

                break;

            case '#internal_options':
                break;

            default:
                throw new Regex\Exception(
                    'Unsupported node: %s.',
                    0,
                    $element->getId()
                );
        }

        return;
    }
}
