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
use Hoa\Compiler\Llk\Llk as SUT;
use Hoa\File;
use Hoa\Test;

/**
 * Class \Hoa\Compiler\Test\Unit\Llk\Llk.
 *
 * Test suite of the LL(k) helper class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Llk extends Test\Unit\Suite
{
    public function case_load_empty()
    {
        $this
            ->given($stream = new File\ReadWrite('hoa://Test/Vfs/Empty.pp?type=file'))
            ->exception(function () use ($stream) {
                SUT::load($stream);
            })
                ->isInstanceOf(LUT\Exception::class)
                ->hasMessage(
                    'The grammar is empty: Nothing to read on the stream ' .
                    'hoa://Test/Vfs/Empty.pp?type=file.'
                );
    }

    public function case_load()
    {
        $this
            ->given(
                $stream = new File\ReadWrite('hoa://Test/Vfs/Grammar.pp?type=file'),
                $stream->writeAll(
                    '%pragma  hello world' . "\n" .
                    '%token   foobar bazqux' . "\n" .
                    'ruleA:' . "\n" .
                    '    <foobar>'
                ),

                $_ruleA = new LUT\Llk\Rule\Token('ruleA', 'foobar', null, -1, true),
                $_ruleA->setPPRepresentation(' <foobar>')
            )
            ->when($result = SUT::load($stream))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Llk\Parser::class)
                ->array($result->getPragmas())
                    ->isEqualTo([
                        'hello' => 'world'
                    ])
                ->array($result->getTokens())
                    ->isEqualTo([
                        'default' => [
                            'foobar' => 'bazqux'
                        ]
                    ])
                ->array($result->getRules())
                    ->isEqualTo([
                        'ruleA' => $_ruleA
                    ]);
    }

    public function case_save()
    {
        $this
            ->given(
                $stream = new File\Read('hoa://Library/Compiler/Llk/Llk.pp'),
                $parser = SUT::load($stream)
            )
            ->when($result = SUT::save($parser, 'Foobar'))
            ->then
                ->string($result)
                    ->isEqualTo(<<<'OUTPUT'
class Foobar extends \Hoa\Compiler\Llk\Parser
{
    public function __construct()
    {
        parent::__construct(
            [
                'default' => [
                    'skip' => '\s',
                    'or' => '\|',
                    'zero_or_one' => '\?',
                    'one_or_more' => '\+',
                    'zero_or_more' => '\*',
                    'n_to_m' => '\{[0-9]+,[0-9]+\}',
                    'zero_to_m' => '\{,[0-9]+\}',
                    'n_or_more' => '\{[0-9]+,\}',
                    'exactly_n' => '\{[0-9]+\}',
                    'token' => '[a-zA-Z_][a-zA-Z0-9_]*',
                    'skipped' => '::',
                    'kept_' => '<',
                    '_kept' => '>',
                    'named' => '\(\)',
                    'node' => '#[a-zA-Z_][a-zA-Z0-9_]*(:[mM])?',
                    'capturing_' => '\(',
                    '_capturing' => '\)',
                    'unification_' => '\[',
                    'unification' => '[0-9]+',
                    '_unification' => '\]',
                ],
            ],
            [
                0 => new \Hoa\Compiler\Llk\Rule\Concatenation(0, ['choice'], null),
                'rule' => new \Hoa\Compiler\Llk\Rule\Concatenation('rule', [0], '#rule'),
                2 => new \Hoa\Compiler\Llk\Rule\Token(2, 'or', null, -1, false),
                3 => new \Hoa\Compiler\Llk\Rule\Concatenation(3, [2, 'concatenation'], '#choice'),
                4 => new \Hoa\Compiler\Llk\Rule\Repetition(4, 0, -1, 3, null),
                'choice' => new \Hoa\Compiler\Llk\Rule\Concatenation('choice', ['concatenation', 4], null),
                6 => new \Hoa\Compiler\Llk\Rule\Concatenation(6, ['repetition'], '#concatenation'),
                7 => new \Hoa\Compiler\Llk\Rule\Repetition(7, 0, -1, 6, null),
                'concatenation' => new \Hoa\Compiler\Llk\Rule\Concatenation('concatenation', ['repetition', 7], null),
                9 => new \Hoa\Compiler\Llk\Rule\Concatenation(9, ['quantifier'], '#repetition'),
                10 => new \Hoa\Compiler\Llk\Rule\Repetition(10, 0, 1, 9, null),
                11 => new \Hoa\Compiler\Llk\Rule\Token(11, 'node', null, -1, true),
                12 => new \Hoa\Compiler\Llk\Rule\Repetition(12, 0, 1, 11, null),
                'repetition' => new \Hoa\Compiler\Llk\Rule\Concatenation('repetition', ['simple', 10, 12], null),
                14 => new \Hoa\Compiler\Llk\Rule\Token(14, 'capturing_', null, -1, false),
                15 => new \Hoa\Compiler\Llk\Rule\Token(15, '_capturing', null, -1, false),
                16 => new \Hoa\Compiler\Llk\Rule\Concatenation(16, [14, 'choice', 15], null),
                17 => new \Hoa\Compiler\Llk\Rule\Token(17, 'skipped', null, -1, false),
                18 => new \Hoa\Compiler\Llk\Rule\Token(18, 'token', null, -1, true),
                19 => new \Hoa\Compiler\Llk\Rule\Token(19, 'unification_', null, -1, false),
                20 => new \Hoa\Compiler\Llk\Rule\Token(20, 'unification', null, -1, true),
                21 => new \Hoa\Compiler\Llk\Rule\Token(21, '_unification', null, -1, false),
                22 => new \Hoa\Compiler\Llk\Rule\Concatenation(22, [19, 20, 21], null),
                23 => new \Hoa\Compiler\Llk\Rule\Repetition(23, 0, 1, 22, null),
                24 => new \Hoa\Compiler\Llk\Rule\Token(24, 'skipped', null, -1, false),
                25 => new \Hoa\Compiler\Llk\Rule\Concatenation(25, [17, 18, 23, 24], '#skipped'),
                26 => new \Hoa\Compiler\Llk\Rule\Token(26, 'kept_', null, -1, false),
                27 => new \Hoa\Compiler\Llk\Rule\Token(27, 'token', null, -1, true),
                28 => new \Hoa\Compiler\Llk\Rule\Token(28, 'unification_', null, -1, false),
                29 => new \Hoa\Compiler\Llk\Rule\Token(29, 'unification', null, -1, true),
                30 => new \Hoa\Compiler\Llk\Rule\Token(30, '_unification', null, -1, false),
                31 => new \Hoa\Compiler\Llk\Rule\Concatenation(31, [28, 29, 30], null),
                32 => new \Hoa\Compiler\Llk\Rule\Repetition(32, 0, 1, 31, null),
                33 => new \Hoa\Compiler\Llk\Rule\Token(33, '_kept', null, -1, false),
                34 => new \Hoa\Compiler\Llk\Rule\Concatenation(34, [26, 27, 32, 33], '#kept'),
                35 => new \Hoa\Compiler\Llk\Rule\Token(35, 'token', null, -1, true),
                36 => new \Hoa\Compiler\Llk\Rule\Token(36, 'named', null, -1, false),
                37 => new \Hoa\Compiler\Llk\Rule\Concatenation(37, [35, 36], null),
                'simple' => new \Hoa\Compiler\Llk\Rule\Choice('simple', [16, 25, 34, 37], null),
                39 => new \Hoa\Compiler\Llk\Rule\Token(39, 'zero_or_one', null, -1, true),
                40 => new \Hoa\Compiler\Llk\Rule\Token(40, 'one_or_more', null, -1, true),
                41 => new \Hoa\Compiler\Llk\Rule\Token(41, 'zero_or_more', null, -1, true),
                42 => new \Hoa\Compiler\Llk\Rule\Token(42, 'n_to_m', null, -1, true),
                43 => new \Hoa\Compiler\Llk\Rule\Token(43, 'n_or_more', null, -1, true),
                44 => new \Hoa\Compiler\Llk\Rule\Token(44, 'exactly_n', null, -1, true),
                'quantifier' => new \Hoa\Compiler\Llk\Rule\Choice('quantifier', [39, 40, 41, 42, 43, 44], null),
            ],
            [
            ]
        );

        $this->getRule('rule')->setDefaultId('#rule');
        $this->getRule('rule')->setPPRepresentation(' choice()');
        $this->getRule('choice')->setPPRepresentation(' concatenation() ( ::or:: concatenation() #choice )*');
        $this->getRule('concatenation')->setPPRepresentation(' repetition() ( repetition() #concatenation )*');
        $this->getRule('repetition')->setPPRepresentation(' simple() ( quantifier() #repetition )? <node>?');
        $this->getRule('simple')->setPPRepresentation(' ::capturing_:: choice() ::_capturing:: | ::skipped:: <token> ( ::unification_:: <unification> ::_unification:: )? ::skipped:: #skipped | ::kept_:: <token> ( ::unification_:: <unification> ::_unification:: )? ::_kept:: #kept | <token> ::named::');
        $this->getRule('quantifier')->setPPRepresentation(' <zero_or_one> | <one_or_more> | <zero_or_more> | <n_to_m> | <n_or_more> | <exactly_n>');
    }
}

OUTPUT
);
    }

    public function case_parse_tokens()
    {
        $this
            ->given(
                $pp =
                    '%token  foobar1            bazqux1' . "\n" .
                    '%token  sourceNS1:foobar2  bazqux2' . "\n" .
                    '%token  sourceNS2:foobar3  bazqux3  -> destinationNS' . "\n" .
                    '%token  foobar4            barqux4  -> destinationNS'
            )
            ->when($result = SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo'))
            ->then
                ->variable($result)
                    ->isNull()
                ->array($tokens)
                    ->isEqualTo([
                        'default' => [
                            'foobar1'               => 'bazqux1',
                            'foobar4:destinationNS' => 'barqux4'
                        ],
                        'sourceNS1' => [
                            'foobar2' => 'bazqux2'
                        ],
                        'sourceNS2' => [
                            'foobar3:destinationNS' => 'bazqux3'
                        ]
                    ])
                ->array($rules)
                    ->isEmpty()
                ->array($pragmas)
                    ->isEmpty();
    }

    public function case_parse_skip_tokens()
    {
        $this
            ->given(
                $pp =
                '%skip  foobar1            bazqux1' . "\n" .
                '%skip  foobar2            bazqux2' . "\n" .
                '%skip  foobar3            bazqux3' . "\n" .
                '%skip  sourceNS1:foobar4  bazqux4' . "\n" .
                '%skip  sourceNS1:foobar5  bazqux5' . "\n" .
                '%skip  sourceNS2:foobar6  bazqux6' . "\n"
            )
            ->when($result = SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo'))
            ->then
                ->variable($result)
                    ->isNull()
                ->array($tokens)
                    ->isEqualTo([
                        'default' => [
                            'skip' => '(?:(?:bazqux1|bazqux2)|bazqux3)',
                        ],
                        'sourceNS1' => [
                            'skip' => '(?:bazqux4|bazqux5)'
                        ],
                        'sourceNS2' => [
                            'skip' => 'bazqux6'
                        ]
                    ])
                ->array($rules)
                    ->isEmpty()
                ->array($pragmas)
                    ->isEmpty();
    }

    public function case_parse_pragmas()
    {
        $this
            ->given(
                $pp =
                    '%pragma  truly   true' . "\n" .
                    '%pragma  falsy   false' . "\n" .
                    '%pragma  numby   42' . "\n" .
                    '%pragma  foobar  hello' . "\n" .
                    '%pragma  bazqux  "world!"  ' . "\n"
            )
            ->when($result = SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo'))
            ->then
                ->variable($result)
                    ->isNull()
                ->array($tokens)
                    ->isEqualTo(['default' => []])
                ->array($rules)
                    ->isEmpty()
                ->array($pragmas)
                    ->isIdenticalTo([
                        'truly'  => true,
                        'falsy'  => false,
                        'numby'  => 42,
                        'foobar' => 'hello',
                        'bazqux' => '"world!"'
                    ]);
    }

    public function case_unrecognized_instructions()
    {
        $this
            ->given(
                $pp =
                    '// shift line' . "\n" .
                    '%foobar baz qux' . "\n"
            )
            ->exception(function () use ($pp) {
                SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo');
            })
                ->isInstanceOf(LUT\Exception::class)
                ->hasMessage(
                    'Unrecognized instructions:' . "\n" .
                    '    %foobar baz qux' . "\n" .
                    'in file streamFoo at line 2.'
                );
    }

    public function case_parse_rules()
    {
        $this
            ->given(
                $pp =
                    'ruleA:' . "\n" .
                    ' single space' . "\n" .
                    ' single space' . "\n" .
                    'ruleB:' . "\n" .
                    '    many spaces' . "\n" .
                    "\t" . 'single tab' . "\n" .
                    'ruleC:' . "\n" .
                    "\t\t" . 'many tabs' . "\n"
            )
            ->when($result = SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo'))
            ->then
                ->variable($result)
                    ->isNull()
                ->array($tokens)
                    ->isEqualTo(['default' => []])
                ->array($rules)
                    ->isEqualTo([
                        'ruleA' => ' single space single space',
                        'ruleB' => ' many spaces single tab',
                        'ruleC' => ' many tabs'
                    ])
                ->array($pragmas)
                    ->isEmpty();
    }

    public function case_parse_skip_comments()
    {
        $this
            ->given(
                $pp =
                    '// Hello,' . "\n" .
                    '//   World!'
            )
            ->when($result = SUT::parsePP($pp, $tokens, $rules, $pragmas, 'streamFoo'))
            ->then
                ->variable($result)
                    ->isNull()
                ->array($tokens)
                    ->isEqualTo(['default' => []])
                ->array($rules)
                    ->isEmpty()
                ->array($pragmas)
                    ->isEmpty();
    }
}
