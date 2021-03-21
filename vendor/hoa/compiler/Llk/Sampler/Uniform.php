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

namespace Hoa\Compiler\Llk\Sampler;

use Hoa\Compiler;
use Hoa\Math;
use Hoa\Visitor;

/**
 * Class \Hoa\Compiler\Llk\Sampler\Uniform.
 *
 * This generator aims at producing random and uniform a sequence of a fixed
 * size. We use the recursive method to count all possible sub-structures of
 * size n. The counting helps to compute cumulative distribution functions,
 * which guide the exploration.
 * Repetition unfolding: upper bound of + and * is set to n.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Uniform extends Sampler
{
    /**
     * Data (pre-computing).
     *
     * @var array
     */
    protected $_data   = [];

    /**
     * Bound.
     *
     * @var int
     */
    protected $_length = 5;



    /**
     * Construct a generator.
     *
     * @param   \Hoa\Compiler\Llk\Parser  $compiler        Compiler/parser.
     * @param   \Hoa\Visitor\Visit        $tokenSampler    Token sampler.
     */
    public function __construct(
        Compiler\Llk\Parser $compiler,
        Visitor\Visit       $tokenSampler,
        $length = 5
    ) {
        parent::__construct($compiler, $tokenSampler);

        foreach ($this->_rules as $name => $_) {
            $this->_data[$name] = [];
        }

        $this->setLength($length);
        $this->_sampler = new Math\Sampler\Random();

        return;
    }

    /**
     * The random and uniform algorithm.
     *
     * @param   \Hoa\Compiler\Llk\Rule  $rule    Rule to start.
     * @param   int                     $n       Size.
     * @return  string
     */
    public function uniform(Compiler\Llk\Rule $rule = null, $n = -1)
    {
        if (null === $rule && -1 === $n) {
            $rule = $this->_rules[$this->_rootRuleName];
            $n    = $this->getLength();
        }

        $data     = &$this->_data[$rule->getName()][$n];
        $computed = $data['n'];

        if (0 === $n || 0 === $computed) {
            return null;
        }

        if ($rule instanceof Compiler\Llk\Rule\Choice) {
            $children = $rule->getChildren();
            $stat     = [];

            foreach ($children as $c => $child) {
                $stat[$c] = $this->_data[$child][$n]['n'];
            }

            $i = $this->_sampler->getInteger(1, $computed);

            for ($e = 0, $b = $stat[$e], $max = count($stat) - 1;
                $e < $max && $i > $b;
                $b += $stat[++$e]);

            return $this->uniform($this->_rules[$children[$e]], $n);
        } elseif ($rule instanceof Compiler\Llk\Rule\Concatenation) {
            $children  = $rule->getChildren();
            $out       = null;
            $Γ         = $data['Γ'];
            $γ         = $Γ[$this->_sampler->getInteger(0, count($Γ) - 1)];

            foreach ($children as $i => $child) {
                $out .= $this->uniform($this->_rules[$child], $γ[$i]);
            }

            return $out;
        } elseif ($rule instanceof Compiler\Llk\Rule\Repetition) {
            $out   =  null;
            $stat  = &$data['xy'];
            $child =  $this->_rules[$rule->getChildren()];
            $b     =  0;
            $i     =  $this->_sampler->getInteger(1, $computed);

            foreach ($stat as $α => $st) {
                if ($i <= $b += $st['n']) {
                    break;
                }
            }

            $Γ = &$st['Γ'];
            $γ = &$Γ[$this->_sampler->getInteger(0, count($Γ) - 1)];

            for ($j = 0; $j < $α; ++$j) {
                $out .= $this->uniform($child, $γ[$j]);
            }

            return $out;
        } elseif ($rule instanceof Compiler\Llk\Rule\Token) {
            return $this->generateToken($rule);
        }

        return null;
    }

    /**
     * Recursive method applied to our problematic.
     *
     * @param   \Hoa\Compiler\Llk\Rule  $rule    Rule to start.
     * @param   int                     $n       Size.
     * @return  int
     */
    public function count(Compiler\Llk\Rule $rule = null, $n = -1)
    {
        if (null === $rule || -1 === $n) {
            return 0;
        }

        $ruleName = $rule->getName();

        if (isset($this->_data[$ruleName][$n])) {
            return $this->_data[$ruleName][$n]['n'];
        }

        $this->_data[$ruleName][$n] =  ['n' => 0];
        $out                        = &$this->_data[$ruleName][$n]['n'];
        $rule                       =  $this->_rules[$ruleName];

        if ($rule instanceof Compiler\Llk\Rule\Choice) {
            foreach ($rule->getChildren() as $child) {
                $out += $this->count($this->_rules[$child], $n);
            }
        } elseif ($rule instanceof Compiler\Llk\Rule\Concatenation) {
            $children = $rule->getChildren();
            $Γ        = new Math\Combinatorics\Combination\Gamma(
                count($children),
                $n
            );
            $this->_data[$ruleName][$n]['Γ'] = [];
            $handle                          = &$this->_data[$ruleName][$n]['Γ'];

            foreach ($Γ as $γ) {
                $oout = 1;

                foreach ($γ as $α => $_γ) {
                    $oout *= $this->count($this->_rules[$children[$α]], $_γ);
                }

                if (0 !== $oout) {
                    $handle[] = $γ;
                }

                $out += $oout;
            }
        } elseif ($rule instanceof Compiler\Llk\Rule\Repetition) {
            $this->_data[$ruleName][$n]['xy'] = [];
            $handle                           = &$this->_data[$ruleName][$n]['xy'];
            $child                            =  $this->_rules[$rule->getChildren()];
            $x                                =  $rule->getMin();
            $y                                =  $rule->getMax();

            if (-1 === $y) {
                $y = $n;
            } else {
                $y = min($n, $y);
            }

            if (0 === $x && $x === $y) {
                $out = 1;
            } else {
                for ($α = $x; $α <= $y; ++$α) {
                    $ut         = 0;
                    $handle[$α] = ['n' => 0, 'Γ' => []];
                    $Γ          = new Math\Combinatorics\Combination\Gamma($α, $n);

                    foreach ($Γ as $γ) {
                        $oout = 1;

                        foreach ($γ as $β => $_γ) {
                            $oout *= $this->count($child, $_γ);
                        }

                        if (0 !== $oout) {
                            $handle[$α]['Γ'][] = $γ;
                        }

                        $ut += $oout;
                    }

                    $handle[$α]['n']  = $ut;
                    $out             += $ut;
                }
            }
        } elseif ($rule instanceof Compiler\Llk\Rule\Token) {
            $out = Math\Util::δ($n, 1);
        }

        return $out;
    }

    /**
     * Set upper-bound, the maximum data length.
     *
     * @param   int  $length    Length.
     * @return  int
     * @throws  \Hoa\Compiler\Exception
     */
    public function setLength($length)
    {
        if (0 >= $length) {
            throw new Exception(
                'Length must be greater than 0, given %d.',
                0,
                $length
            );
        }

        $old           = $this->_length;
        $this->_length = $length;
        $this->count(
            $this->_compiler->getRule($this->_rootRuleName),
            $length
        );

        return $old;
    }

    /**
     * Get upper-bound.
     *
     * @return  int
     */
    public function getLength()
    {
        return $this->_length;
    }
}
