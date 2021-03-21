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

namespace Hoa\Compiler\Test\Unit\Llk;

use Hoa\Compiler as LUT;
use Hoa\Compiler\Llk\Lexer as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Compiler\Test\Unit\Llk\Lexer.
 *
 * Test suite of the lexer class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Lexer extends Test\Unit\Suite
{
    public function case_is_a_generator()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abc',
                $tokens = [
                    'default' => [
                        'abc'=> 'abc'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->object($result)
                    ->isInstanceOf(\Generator::class);
    }

    public function case_last_token_is_EOF()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'ghidefabc',
                $tokens = [
                    'default' => [
                        'abc'  => 'abc',
                        'def'  => 'def',
                        'tail' => '\w{3}'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->object($result)
                    ->isInstanceOf(\Generator::class)
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'tail',
                        'value'     => 'ghi',
                        'length'    => 3,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 0
                    ])
                ->let($result->next())
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'def',
                        'value'     => 'def',
                        'length'    => 3,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 3
                    ])
                ->let($result->next())
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'abc',
                        'value'     => 'abc',
                        'length'    => 3,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 6
                    ])
                ->let($result->next())
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'EOF',
                        'value'     => 'EOF',
                        'length'    => 0,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 9
                    ])
                ->let($result->next())
                ->variable($result->current())
                    ->isNull();
    }

    public function case_unrecognized_token()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abczdef',
                $tokens = [
                    'default' => [
                        'abc'  => 'abc',
                        'def'  => 'def'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'abc',
                        'value'     => 'abc',
                        'length'    => 3,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 0
                    ])
                ->exception(function () use ($result) {
                    $result->next();
                })
                    ->isInstanceOf(LUT\Exception\UnrecognizedToken::class)
                    ->hasMessage(
                        'Unrecognized token "z" at line 1 and column 4:' . "\n" .
                        'abczdef' . "\n" .
                        '   ↑'
                    );
    }

    public function case_namespace()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abcdefghiabc',
                $tokens = [
                    'default' => ['abc:one'     => 'abc'],
                    'one'     => ['def:two'     => 'def'],
                    'two'     => ['ghi:default' => 'ghi']
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array(iterator_to_array($result))
                    ->isEqualTo([
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 0
                        ],
                        [
                            'token'     => 'def',
                            'value'     => 'def',
                            'length'    => 3,
                            'namespace' => 'one',
                            'keep'      => true,
                            'offset'    => 3
                        ],
                        [
                            'token'     => 'ghi',
                            'value'     => 'ghi',
                            'length'    => 3,
                            'namespace' => 'two',
                            'keep'      => true,
                            'offset'    => 6
                        ],
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 9
                        ],
                        [
                            'token'     => 'EOF',
                            'value'     => 'EOF',
                            'length'    => 0,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 12
                        ]
                    ]);
    }

    public function case_namespace_with_shift()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abcdefghiabc',
                $tokens = [
                    'default' => ['abc:one'           => 'abc'],
                    'one'     => ['def:two'           => 'def'],
                    'two'     => ['ghi:__shift__ * 2' => 'ghi']
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array(iterator_to_array($result))
                    ->isEqualTo([
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 0
                        ],
                        [
                            'token'     => 'def',
                            'value'     => 'def',
                            'length'    => 3,
                            'namespace' => 'one',
                            'keep'      => true,
                            'offset'    => 3
                        ],
                        [
                            'token'     => 'ghi',
                            'value'     => 'ghi',
                            'length'    => 3,
                            'namespace' => 'two',
                            'keep'      => true,
                            'offset'    => 6
                        ],
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 9
                        ],
                        [
                            'token'     => 'EOF',
                            'value'     => 'EOF',
                            'length'    => 0,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 12
                        ]
                    ]);
    }

    public function case_namespace_shift_too_much()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abcdefghiabc',
                $tokens = [
                    'default' => ['abc:__shift__' => 'abc']
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->exception(function () use ($result) {
                    $result->next();
                })
                    ->isInstanceOf(LUT\Exception\Lexer::class)
                    ->hasMessage(
                        'Cannot shift namespace 1-times, from token abc ' .
                        'in namespace default, because the stack contains ' .
                        'only 0 namespaces.'
                    );
    }

    public function case_namespace_does_not_exist()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abcdef',
                $tokens = [
                    'default' => [
                        'abc:foo' => 'abc',
                        'def'     => 'def'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->exception(function () use ($result) {
                    $result->next();
                })
                    ->isInstanceOf(LUT\Exception\Lexer::class)
                    ->hasMessage(
                        'Namespace foo does not exist, called by token abc ' .
                        'in namespace default.'
                    );
    }

    public function case_skip()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abc def   ghi  abc',
                $tokens = [
                    'default' => [
                        'skip' => '\s+',
                        'abc'  => 'abc',
                        'def'  => 'def',
                        'ghi'  => 'ghi'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array(iterator_to_array($result))
                    ->isEqualTo([
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 0
                        ],
                        [
                            'token'     => 'def',
                            'value'     => 'def',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 4
                        ],
                        [
                            'token'     => 'ghi',
                            'value'     => 'ghi',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 10
                        ],
                        [
                            'token'     => 'abc',
                            'value'     => 'abc',
                            'length'    => 3,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 15
                        ],
                        [
                            'token'     => 'EOF',
                            'value'     => 'EOF',
                            'length'    => 0,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 18
                        ]
                    ]);
    }

    public function case_match_empty_lexeme()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = 'abcdef',
                $tokens = [
                    'default' => [
                        'abc' => '\d?',
                        'def' => 'def'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->exception(function () use ($result) {
                    $result->next();
                })
                    ->isInstanceOf(LUT\Exception\Lexer::class)
                    ->hasMessage(
                        'A lexeme must not match an empty value, which is ' .
                        'the case of "abc" (\d?).'
                    );
    }

    public function case_unicode_enabled_by_default()
    {
        $this
            ->given(
                $lexer  = new SUT(),
                $datum  = '…ß',
                $tokens = [
                    'default' => [
                        'foo' => '…',
                        'bar' => '\w'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array(iterator_to_array($result))
                    ->isEqualTo([
                        [
                            'token'     => 'foo',
                            'value'     => '…',
                            'length'    => 1,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 0
                        ],
                        [
                            'token'     => 'bar',
                            'value'     => 'ß',
                            'length'    => 1,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 3
                        ],
                        [
                            'token'     => 'EOF',
                            'value'     => 'EOF',
                            'length'    => 0,
                            'namespace' => 'default',
                            'keep'      => true,
                            'offset'    => 5
                        ]
                    ]);
    }

    public function case_unicode_disabled()
    {
        $this
            ->given(
                $lexer  = new SUT(['lexer.unicode' => false]),
                $datum  = '…ß',
                $tokens = [
                    'default' => [
                        'foo' => '…',
                        'bar' => '\w'
                    ]
                ]
            )
            ->when($result = $lexer->lexMe($datum, $tokens))
            ->then
                ->array($result->current())
                    ->isEqualTo([
                        'token'     => 'foo',
                        'value'     => '…',
                        'length'    => 1,
                        'namespace' => 'default',
                        'keep'      => true,
                        'offset'    => 0
                    ])
                ->exception(function () use ($result) {
                    $result->next();
                })
                    ->isInstanceOf(LUT\Exception\UnrecognizedToken::class)
                    ->hasMessage(
                        'Unrecognized token "ß" at line 1 and column 4:' . "\n" .
                        '…ß' . "\n" .
                        ' ↑'
                    );
    }
}
