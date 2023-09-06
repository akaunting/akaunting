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

namespace Hoa\Compiler\Test\Integration;

use Hoa\Compiler as LUT;
use Hoa\File;
use Hoa\Test;

/**
 * Class \Hoa\Compiler\Test\Integration\Documentation.
 *
 * Test suite of the examples in the documentation.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Documentation extends Test\Integration\Suite implements Test\Decorrelated
{
    public function case_whole_process()
    {
        $_grammar = <<<GRAMMAR
%skip   space          \s
// Scalars.
%token  true           true
%token  false          false
%token  null           null
// Strings.
%token  quote_         "        -> string
%token  string:string  [^"]+
%token  string:_quote  "        -> default
// Objects.
%token  brace_         {
%token _brace          }
// Arrays.
%token  bracket_       \[
%token _bracket        \]
// Rest.
%token  colon          :
%token  comma          ,
%token  number         \d+

value:
    <true> | <false> | <null> | string() | object() | array() | number()

string:
    ::quote_:: <string> ::_quote::

number:
    <number>

#object:
    ::brace_:: pair() ( ::comma:: pair() )* ::_brace::

#pair:
    string() ::colon:: value()

#array:
    ::bracket_:: value() ( ::comma:: value() )* ::_bracket::
GRAMMAR;
        $_result = <<<GRAMMAR
>  #object
>  >  #pair
>  >  >  token(string:string, foo)
>  >  >  token(true, true)
>  >  #pair
>  >  >  token(string:string, bar)
>  >  >  #array
>  >  >  >  token(null, null)
>  >  >  >  token(number, 42)

GRAMMAR;

        $this
            ->given(
                $grammar = new File\ReadWrite('hoa://Test/Vfs/Json.pp?type=file'),
                $grammar->writeAll($_grammar),
                $compiler = LUT\Llk::load($grammar)
            )
            ->when($ast = $compiler->parse('{"foo": true, "bar": [null, 42]}'))
            ->then
                ->object($ast)

            ->given($dump = new LUT\Visitor\Dump())
            ->when($result = $dump->visit($ast))
            ->then
                ->string($result)
                    ->isEqualTo($_result);
    }
}
