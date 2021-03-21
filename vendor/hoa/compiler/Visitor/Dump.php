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

namespace Hoa\Compiler\Visitor;

use Hoa\Visitor;

/**
 * Class \Hoa\Compiler\Visitor\Dump.
 *
 * Dump AST produced by LL(k) compiler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Dump implements Visitor\Visit
{
    /**
     * Indentation depth.
     *
     * @var int
     */
    protected static $_i = 0;



    /**
     * Visit an element.
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    public function visit(
        Visitor\Element $element,
        &$handle = null,
        $eldnah  = null
    ) {
        ++self::$_i;

        $out  = str_repeat('>  ', self::$_i) . $element->getId();

        if (null !== $value = $element->getValue()) {
            $out .=
                '(' .
                ('default' !== $value['namespace']
                    ? $value['namespace'] . ':'
                    : '') .
                $value['token'] . ', ' .
                $value['value'] . ')';
        }

        $data = $element->getData();

        if (!empty($data)) {
            $out .= ' ' . $this->dumpData($data);
        }

        $out .= "\n";

        foreach ($element->getChildren() as $child) {
            $out .= $child->accept($this, $handle, $eldnah);
        }

        --self::$_i;

        return $out;
    }

    /**
     * Dump data.
     *
     * @param   mixed  $data    Data.
     * @return  string
     */
    protected function dumpData($data)
    {
        $out = null;

        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            $out .= '[' . $key . ' => ' . $this->dumpData($value) . ']';
        }

        return $out;
    }
}
