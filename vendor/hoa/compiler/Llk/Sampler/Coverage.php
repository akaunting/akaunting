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
use Hoa\Iterator;

/**
 * Class \Hoa\Compiler\Llk\Sampler\Coverage.
 *
 * This generator aims at producing data that activate all the branches of the
 * grammar rules.
 * A rule is said to be covered if and only if its sub-rules have all been
 * covered. A token is said to be covered if it has been successfully used in a
 * data generation.
 * To ensure diversity, a random choice is made amongst the remaining sub-rules
 * of a choice-point to cover.
 * Finally, we use boundary test generation heuristics to avoid combinatorial
 * explosion and guarantee the termination, i.e. we bound repetition operators
 * as follow:
 *      • * is bounded to 0, 1 or 2;
 *      • + is unfolded 1 or 2 times;
 *      • {x,y} is unfolded x, x + 1, y - 1 and y times.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Coverage extends Sampler implements Iterator
{
    /**
     * Stack of rules to explore.
     *
     * @var array
     */
    protected $_todo         = null;

    /**
     * Stack of rules that have already been covered.
     *
     * @var array
     */
    protected $_trace        = null;

    /**
     * Produced test cases.
     *
     * @var array
     */
    protected $_tests        = null;

    /**
     * Covered rules: ruleName to structure that contains the choice point and
     * 0 for uncovered, 1 for covered, -1 for failed and .5 for in progress.
     *
     * @var array
     */
    protected $_coveredRules = null;

    /**
     * Current iterator key.
     *
     * @var int
     */
    protected $_key          = -1;

    /**
     * Current iterator value.
     *
     * @var string
     */
    protected $_current      = null;



    /**
     * Get the current iterator value.
     *
     * @return  string
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Get the current iterator key.
     *
     * @return  int
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Useless here.
     *
     * @return  void
     */
    public function next()
    {
        return;
    }

    /**
     * Rewind the internal iterator pointer.
     *
     * @return  void
     */
    public function rewind()
    {
        $this->_key          = -1;
        $this->_current      = null;
        $this->_tests        = [];
        $this->_coveredRules = [];

        foreach ($this->_rules as $name => $rule) {
            $this->_coveredRules[$name] = [];

            if ($rule instanceof Compiler\Llk\Rule\Repetition) {
                $min  = $rule->getMin();
                $min1 = $min + 1;
                $max  = -1 == $rule->getMax() ? 2 : $rule->getMax();
                $max1 = $max - 1;

                if ($min == $max) {
                    $this->_coveredRules[$name][$min]  = 0;
                } else {
                    $this->_coveredRules[$name][$min]  = 0;
                    $this->_coveredRules[$name][$min1] = 0;
                    $this->_coveredRules[$name][$max1] = 0;
                    $this->_coveredRules[$name][$max]  = 0;
                }
            } elseif ($rule instanceof Compiler\Llk\Rule\Choice) {
                for ($i = 0, $max = count($rule->getChildren()); $i < $max; ++$i) {
                    $this->_coveredRules[$name][$i] = 0;
                }
            } else {
                $this->_coveredRules[$name][0] = 0;
            }
        }

        return;
    }

    /**
     * Compute the current iterator value, i.e. generate a new solution.
     *
     * @return  bool
     */
    public function valid()
    {
        $ruleName = $this->_rootRuleName;

        if (true !== in_array(0,  $this->_coveredRules[$ruleName]) &&
            true !== in_array(.5, $this->_coveredRules[$ruleName])) {
            return false;
        }

        $this->_trace = [];
        $this->_todo  = [new Compiler\Llk\Rule\Entry(
            $ruleName,
            $this->_coveredRules
        )];

        $result = $this->unfold();

        if (true !== $result) {
            return false;
        }

        $handle = null;

        foreach ($this->_trace as $trace) {
            if ($trace instanceof Compiler\Llk\Rule\Token) {
                $handle .= $this->generateToken($trace);
            }
        }

        ++$this->_key;
        $this->_current = $handle;
        $this->_tests[] = $this->_trace;

        foreach ($this->_coveredRules as $key => $value) {
            foreach ($value as $k => $v) {
                if (-1 == $v) {
                    $this->_coveredRules[$key][$k] = 0;
                }
            }
        }

        return true;
    }

    /**
     * Unfold rules from the todo stack.
     *
     * @return  bool
     */
    protected function unfold()
    {
        while (0 < count($this->_todo)) {
            $pop = array_pop($this->_todo);

            if ($pop instanceof Compiler\Llk\Rule\Ekzit) {
                $this->_trace[] = $pop;
                $this->updateCoverage($pop);
            } else {
                $out = $this->coverage($this->_rules[$pop->getRule()]);

                if (true !== $out && true !== $this->backtrack()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * The coverage algorithm.
     *
     * @param   \Hoa\Compiler\Llk\Rule  $rule    Rule to cover.
     * @return  bool
     */
    protected function coverage(Compiler\Llk\Rule $rule)
    {
        $children = $rule->getChildren();

        if ($rule instanceof Compiler\Llk\Rule\Repetition) {
            $uncovered  = [];
            $inprogress = [];
            $already    = [];

            foreach ($this->_coveredRules[$rule->getName()] as $child => $value) {
                if (0 == $value || .5 == $value) {
                    $uncovered[]  = $child;
                } elseif (-1 == $value) {
                    $inprogress[] = $child;
                } else {
                    $already[]    = $child;
                }
            }

            if (empty($uncovered)) {
                if (empty($already)) {
                    $rand = $inprogress[rand(
                        0,
                        count($inprogress) - 1
                    )];
                } else {
                    $rand = $already[rand(
                        0,
                        count($already) - 1
                    )];
                }

                $this->_trace[] = new Compiler\Llk\Rule\Entry(
                    $rule->getName(),
                    $this->_coveredRules,
                    $this->_todo
                );
                $this->_todo[]  = new Compiler\Llk\Rule\Ekzit(
                    $rule->getName(),
                    $rand
                );

                if ($this->_rules[$children] instanceof Compiler\Llk\Rule\Token) {
                    for ($i = 0; $i < $rand; ++$i) {
                        $this->_todo[] = new Compiler\Llk\Rule\Entry(
                            $children,
                            $this->_coveredRules,
                            $this->_todo
                        );
                    }
                } else {
                    $sequence = $this->extract([$children]);

                    if (null === $sequence) {
                        return null;
                    }

                    for ($i = 0; $i < $rand; ++$i) {
                        foreach ($sequence as $seq) {
                            $this->_trace[] = $seq;

                            if ($seq instanceof Compiler\Llk\Rule\Ekzit) {
                                $this->updateCoverage($seq);
                            }
                        }
                    }
                }
            } else {
                $rand                                         = $uncovered[rand(0, count($uncovered) - 1)];
                $this->_coveredRules[$rule->getName()][$rand] = -1;
                $this->_trace[]                               = new Compiler\Llk\Rule\Entry(
                    $rule->getName(),
                    $this->_coveredRules,
                    $this->_todo
                );
                $this->_todo[] = new Compiler\Llk\Rule\Ekzit(
                    $rule->getName(),
                    $rand
                );

                for ($i= 0 ; $i < $rand; ++$i) {
                    $this->_todo[] = new Compiler\Llk\Rule\Entry(
                        $children,
                        $this->_coveredRules,
                        $this->_todo
                    );
                }
            }

            return true;
        } elseif ($rule instanceof Compiler\Llk\Rule\Choice) {
            $uncovered  = [];
            $inprogress = [];
            $already    = [];

            foreach ($this->_coveredRules[$rule->getName()] as $child => $value) {
                if (0 == $value || .5 == $value) {
                    $uncovered[]  = $child;
                } elseif (-1 == $value) {
                    $inprogress[] = $child;
                } else {
                    $already[]    = $child;
                }
            }

            if (empty($uncovered)) {
                $this->_trace[] = new Compiler\Llk\Rule\Entry(
                    $rule->getName(),
                    $this->_coveredRules,
                    $this->_todo
                );
                $sequence = $this->extract($children);

                if (null === $sequence) {
                    return null;
                }

                foreach ($sequence as $seq) {
                    $this->_trace[] = $seq;

                    if ($seq instanceof Compiler\Llk\Rule\Ekzit) {
                        $this->updateCoverage($seq);
                    }
                }

                if (empty($already)) {
                    $rand = $inprogress[rand(
                        0,
                        count($inprogress) - 1
                    )];
                } else {
                    $rand = $already[rand(
                        0,
                        count($already) - 1
                    )];
                }

                $this->_todo[] = new Compiler\Llk\Rule\Ekzit(
                    $rule->getName(),
                    $rand
                );
            } else {
                $rand           = $uncovered[rand(0, count($uncovered) - 1)];
                $this->_trace[] = new Compiler\Llk\Rule\Entry(
                    $rule->getName(),
                    $this->_coveredRules,
                    $this->_todo
                );
                $this->_coveredRules[$rule->getName()][$rand] = -1;
                $this->_todo[]                                = new Compiler\Llk\Rule\Ekzit(
                    $rule->getName(),
                    $rand
                );
                $this->_todo[]  = new Compiler\Llk\Rule\Entry(
                    $children[$rand],
                    $this->_coveredRules,
                    $this->_todo
                );
            }

            return true;
        } elseif ($rule instanceof Compiler\Llk\Rule\Concatenation) {
            $this->_coveredRules[$rule->getName()][0] = -1;
            $this->_trace[]                           = new Compiler\Llk\Rule\Entry(
                $rule->getName(),
                false
            );
            $this->_todo[]  = new Compiler\Llk\Rule\Ekzit(
                $rule->getName(),
                false
            );

            for ($i = count($children) - 1; $i >= 0; --$i) {
                $this->_todo[] = new Compiler\Llk\Rule\Entry(
                    $children[$i],
                    false,
                    $this->_todo
                );
            }

            return true;
        } elseif ($rule instanceof Compiler\Llk\Rule\Token) {
            $this->_trace[] = new Compiler\Llk\Rule\Entry(
                $rule->getName(),
                false
            );
            $this->_trace[] = $rule;
            $this->_todo[]  = new Compiler\Llk\Rule\Ekzit(
                $rule->getName(),
                false
            );

            return true;
        }

        return false;
    }

    /**
     * Extract a given sequence from existing traces.
     *
     * @param   array  $rules    Rules to consider.
     * @return  array
     */
    protected function extract(array $rules)
    {
        $out = [];

        foreach ($rules as $rule) {
            foreach ($this->_tests as $test) {
                $opened = 0;

                foreach ($test as $t) {
                    if ($t instanceof Compiler\Llk\Rule\Entry &&
                        $t->getRule() == $rule) {
                        ++$opened;
                    }

                    if (0 < $opened) {
                        $out[] = $t;

                        if ($t instanceof Compiler\Llk\Rule\Ekzit &&
                            $t->getRule() == $rule) {
                            --$opened;

                            if (0 === $opened) {
                                return $out;
                            }
                        }
                    }
                }
            }
        }

        foreach ($rules as $rule) {
            $out    = [];
            $closed = 0;

            foreach ($this->_trace as $t) {
                if ($t instanceof Compiler\Llk\Rule\Ekzit &&
                    $t->getRule() == $rule) {
                    ++$closed;
                }

                if (0 < $closed) {
                    $out[] = $t;

                    if ($t instanceof Compiler\Llk\Rule\Ekzit &&
                        $t->getRule() == $rule) {
                        --$closed;

                        if (0 === $closed) {
                            return array_reverse($out);
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Backtrack to the previous choice-point.
     *
     * @return  bool
     */
    protected function backtrack()
    {
        $found = false;

        do {
            $pop = array_pop($this->_trace);

            if ($pop instanceof Compiler\Llk\Rule\Entry) {
                $rule  = $this->_rules[$pop->getRule()];
                $found = $rule instanceof Compiler\Llk\Rule\Choice ||
                         $rule instanceof Compiler\Llk\Rule\Repetition;
            }
        } while (0 < count($this->_trace) && false === $found);

        if (false === $found) {
            return false;
        }

        $ruleName       = $pop->getRule();
        $this->_covered = $pop->getData();
        $this->_todo    = $pop->getTodo();
        $this->_todo[]  = new Compiler\Llk\Rule\Entry(
            $ruleName,
            $this->_covered,
            $this->_todo
        );

        return true;
    }

    /**
     * Update coverage of a rule.
     *
     * @param   \Hoa\Compiler\Llk\Rule\Ekzit  $rule    Rule to consider.
     * @return  void
     */
    protected function updateCoverage(Compiler\Llk\Rule\Ekzit $Rule)
    {
        $ruleName = $Rule->getRule();
        $child    = $Rule->getData();
        $rule     = $this->_rules[$ruleName];
        $children = $rule->getChildren();

        if ($rule instanceof Compiler\Llk\Rule\Repetition) {
            if (0 === $child) {
                $this->_coveredRules[$ruleName][$child] = 1;
            } else {
                if (true === $this->allCovered($children) ||
                    true === $this->checkRuleRoot($children)) {
                    $this->_coveredRules[$ruleName][$child] = 1;

                    foreach ($this->_coveredRules[$ruleName] as $child => $value) {
                        if (.5 == $value) {
                            $this->_coveredRules[$ruleName][$child] = 1;
                        }
                    }
                } else {
                    $this->_coveredRules[$ruleName][$child] = .5;
                }
            }
        } elseif ($rule instanceof Compiler\Llk\Rule\Choice) {
            if (true === $this->allCovered($children[$child]) ||
                true === $this->checkRuleRoot($children[$child])) {
                $this->_coveredRules[$ruleName][$child] = 1;
            } else {
                $this->_coveredRules[$ruleName][$child] = .5;
            }
        } elseif ($rule instanceof Compiler\Llk\Rule\Concatenation) {
            $isCovered = true;

            for ($i = count($children) - 1; $i >= 0 && true === $isCovered; --$i) {
                if (false === $this->allCovered($children[$i]) &&
                    false === $this->checkRuleRoot($children[$i])) {
                    $isCovered = false;
                }
            }

            $this->_coveredRules[$ruleName][0] = true === $isCovered ? 1 : .5;
        } elseif ($rule instanceof Compiler\Llk\Rule\Token) {
            $this->_coveredRules[$ruleName][0] = 1;
        }

        return;
    }

    /**
     * Check if all rules have been entirely covered.
     *
     * @param   string  $ruleName    Rule name.
     * @return  bool
     */
    protected function allCovered($ruleName)
    {
        foreach ($this->_coveredRules[$ruleName] as $value) {
            if (1 !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a rule is a root rule that is currently being processed.
     *
     * @param   string  $ruleName    Rule name.
     * @return  bool
     */
    protected function checkRuleRoot($ruleName)
    {
        if (true === $this->_rules[$ruleName]->isTransitional()) {
            return false;
        }

        $i  = count($this->_trace) - 1;
        $nb = 0;

        while ($i >= 0) {
            $lastRule = $this->_trace[$i];

            if ($lastRule instanceof Compiler\Llk\Rule\Entry) {
                if ($lastRule->getRule() == $ruleName) {
                    ++$nb;
                }
            } elseif ($lastRule instanceof Compiler\Llk\Rule\Ekzit) {
                if ($lastRule->getRule() == $ruleName) {
                    --$nb;
                }
            }

            --$i;
        }

        return 0 < $nb;
    }
}
