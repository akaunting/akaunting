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

namespace Hoa\Math\Test\Unit\Visitor;

use Hoa\Compiler;
use Hoa\File;
use Hoa\Math as LUT;
use Hoa\Math\Visitor\Arithmetic as CUT;
use Hoa\Regex;
use Hoa\Test;

/**
 * Class \Hoa\Math\Test\Unit\Visitor\Arithmetic.
 *
 * Test suite of the hoa://Library/Math/Arithmetic.pp grammar and the
 * Hoa\Math\Visitor\Arithmetic class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Arithmetic extends Test\Unit\Suite
{
    public function case_visitor_exhaustively()
    {
        $this
            ->given(
                $sampler = new Compiler\Llk\Sampler\BoundedExhaustive(
                    Compiler\Llk\Llk::load(
                        new File\Read('hoa://Library/Math/Test/Unit/Arithmetic.pp')
                    ),
                    new Regex\Visitor\Isotropic(
                        new LUT\Sampler\Random()
                    ),
                    9
                ),
                $compiler = Compiler\Llk\Llk::load(
                    new File\Read('hoa://Library/Math/Arithmetic.pp')
                ),
                $visitor  = new CUT()
            )
            ->executeOnFailure(function () use (&$expression) {
                echo 'Failed expression: ', $expression, '.', "\n";
            })
            ->when(function () use (&$sampler, &$compiler, &$visitor) {
                foreach ($sampler as $i=> $expression) {
                    try {
                        $x = (float) $visitor->visit(
                            $compiler->parse($expression)
                        );
                    } catch (\Exception $e) {
                        continue;
                    }

                    eval('$y = (float) ' . $expression . ';');

                    if (is_nan($x) || is_nan($y)) {
                        $this->boolean(true);

                        continue;
                    }

                    $this
                        ->float($x)
                            ->isNearlyEqualTo($y);
                }
            });
    }

    public function case_visitor_unknown_variable()
    {
        $this
            ->given(
                $compiler     = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor      = new CUT(),
                $variableName = 'unknown_variable'
            )
            ->then
                ->object($compiler->parse($variableName . ' * 2'))
                    ->isInstanceOf('Hoa\Compiler\Llk\TreeNode')
                ->exception(function () use ($variableName, $compiler, $visitor) {
                    $visitor->visit($compiler->parse($variableName . ' * 2'));
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownVariable');
    }

    public function case_visitor_variable()
    {
        $this
            ->given(
                $compiler      = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor       = new CUT(),
                $variableName  = 'a_variable',
                $variableValue = 42
            )
            ->when($visitor->addVariable($variableName, function () use ($variableValue) {
                return $variableValue;
            }))
            ->then
                ->float($visitor->visit($compiler->parse($variableName . ' * 2')))
                    ->isEqualTo($variableValue * 2);
    }

    public function case_visitor_unknown_constant()
    {
        $this
            ->given(
                $compiler      = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor       = new CUT(),
                $constantName  = 'UNKNOWN_CONSTANT'
            )
            ->then
                ->object($compiler->parse($constantName . ' * 2'))
                    ->isInstanceOf('Hoa\Compiler\Llk\TreeNode')
                ->exception(function () use ($constantName, $compiler, $visitor) {
                    $visitor->visit($compiler->parse($constantName . ' * 2'));
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownConstant');
    }

    public function case_visitor_constant()
    {
        $this
            ->given(
                $compiler      = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor       = new CUT(),
                $constantName  = 'A_CONSTANT',
                $constantValue = 42
            )
            ->when($visitor->addConstant($constantName, $constantValue))
            ->then
                ->float($visitor->visit($compiler->parse($constantName . ' * 2')))
                    ->isEqualTo($constantValue * 2);
    }

    public function case_visitor_unknown_function()
    {
        $this
            ->given(
                $compiler       = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor        = new CUT(),
                $functionName   = 'unknown_function'
            )
            ->then
                ->object($compiler->parse($functionName . '() * 2'))
                    ->isInstanceOf('Hoa\Compiler\Llk\TreeNode')
                ->exception(function () use ($functionName, $compiler, $visitor) {
                    $visitor->visit($compiler->parse($functionName . '() * 2'));
                })
                    ->isInstanceOf('Hoa\Math\Exception\UnknownFunction');
    }

    public function case_visitor_function()
    {
        $this
            ->given(
                $compiler       = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor        = new CUT(),
                $functionName   = 'a_function',
                $functionResult = 42
            )
            ->when($visitor->addFunction($functionName, function () use ($functionResult) {
                return $functionResult;
            }))
            ->then
                ->float($visitor->visit($compiler->parse($functionName . '() * 2')))
                    ->isEqualTo($functionResult * 2);
    }

    public function case_change_default_context()
    {
        $this
            ->given(
                $compiler       = Compiler\Llk\Llk::load(new File\Read('hoa://Library/Math/Arithmetic.pp')),
                $visitor        = new CUT(),
                $context        = new \Mock\Hoa\Math\Context(),
                $variableName  = 'a_variable',
                $variableValue = 42
            )
            ->when($context->addVariable($variableName, function () use ($variableValue) { return $variableValue; }))
            ->then
                ->object($visitor->setContext($context))
                    ->isNotIdenticalTo($context)
                ->object($visitor->getContext())
                    ->isIdenticalTo($context)
                ->float($visitor->visit($compiler->parse('abs(' . $variableName . ' - PI)')))
                    ->isEqualTo(abs($variableValue - M_PI))
                ->mock($context)
                    ->call('getFunction')->withArguments('abs')->once
                    ->call('getVariable')->withArguments($variableName)->once
                    ->call('getConstant')->withArguments('PI')->once;
    }
}
