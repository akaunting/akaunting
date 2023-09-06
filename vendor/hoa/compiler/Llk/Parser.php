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

namespace Hoa\Compiler\Llk;

use Hoa\Compiler;
use Hoa\Iterator;

/**
 * Class \Hoa\Compiler\Llk\Parser.
 *
 * LL(k) parser.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Parser
{
    /**
     * List of pragmas.
     *
     * @var array
     */
    protected $_pragmas       = null;

    /**
     * List of skipped tokens.
     *
     * @var array
     */
    protected $_skip          = null;

    /**
     * Associative array (token name => token regex), to be defined in
     * precedence order.
     *
     * @var array
     */
    protected $_tokens        = null;

    /**
     * Rules, to be defined as associative array, name => Rule object.
     *
     * @var array
     */
    protected $_rules         = null;

    /**
     * Lexer iterator.
     *
     * @var \Hoa\Iterator\Lookahead
     */
    protected $_tokenSequence = null;

    /**
     * Possible token causing an error.
     *
     * @var array
     */
    protected $_errorToken    = null;

    /**
     * Trace of activated rules.
     *
     * @var array
     */
    protected $_trace         = [];

    /**
     * Stack of todo list.
     *
     * @var array
     */
    protected $_todo          = null;

    /**
     * AST.
     *
     * @var \Hoa\Compiler\Llk\TreeNode
     */
    protected $_tree          = null;

    /**
     * Current depth while building the trace.
     *
     * @var int
     */
    protected $_depth         = -1;



    /**
     * Construct the parser.
     *
     * @param   array  $tokens     Tokens.
     * @param   array  $rules      Rules.
     * @param   array  $pragmas    Pragmas.
     */
    public function __construct(
        array $tokens  = [],
        array $rules   = [],
        array $pragmas = []
    ) {
        $this->_tokens  = $tokens;
        $this->_rules   = $rules;
        $this->_pragmas = $pragmas;

        return;
    }

    /**
     * Parse :-).
     *
     * @param   string  $text    Text to parse.
     * @param   string  $rule    The axiom, i.e. root rule.
     * @param   bool    $tree    Whether build tree or not.
     * @return  mixed
     * @throws  \Hoa\Compiler\Exception\UnexpectedToken
     */
    public function parse($text, $rule = null, $tree = true)
    {
        $k = 1024;

        if (isset($this->_pragmas['parser.lookahead'])) {
            $k = max(0, intval($this->_pragmas['parser.lookahead']));
        }

        $lexer                = new Lexer($this->_pragmas);
        $this->_tokenSequence = new Iterator\Buffer(
            $lexer->lexMe($text, $this->_tokens),
            $k
        );
        $this->_tokenSequence->rewind();

        $this->_errorToken = null;
        $this->_trace      = [];
        $this->_todo       = [];

        if (false === array_key_exists($rule, $this->_rules)) {
            $rule = $this->getRootRule();
        }

        $closeRule   = new Rule\Ekzit($rule, 0);
        $openRule    = new Rule\Entry($rule, 0, [$closeRule]);
        $this->_todo = [$closeRule, $openRule];

        do {
            $out = $this->unfold();

            if (null  !== $out &&
                'EOF' === $this->_tokenSequence->current()['token']) {
                break;
            }

            if (false === $this->backtrack()) {
                $token  = $this->_errorToken;

                if (null === $this->_errorToken) {
                    $token = $this->_tokenSequence->current();
                }

                $offset = $token['offset'];
                $line   = 1;
                $column = 1;

                if (!empty($text)) {
                    if (0 === $offset) {
                        $leftnl = 0;
                    } else {
                        $leftnl = strrpos($text, "\n", -(strlen($text) - $offset) - 1) ?: 0;
                    }

                    $rightnl = strpos($text, "\n", $offset);
                    $line    = substr_count($text, "\n", 0, $leftnl + 1) + 1;
                    $column  = $offset - $leftnl + (0 === $leftnl);

                    if (false !== $rightnl) {
                        $text = trim(substr($text, $leftnl, $rightnl - $leftnl), "\n");
                    }
                }

                throw new Compiler\Exception\UnexpectedToken(
                    'Unexpected token "%s" (%s) at line %d and column %d:' .
                    "\n" . '%s' . "\n" . str_repeat(' ', $column - 1) . '↑',
                    0,
                    [
                        $token['value'],
                        $token['token'],
                        $line,
                        $column,
                        $text
                    ],
                    $line,
                    $column
                );
            }
        } while (true);

        if (false === $tree) {
            return true;
        }

        $tree = $this->_buildTree();

        if (!($tree instanceof TreeNode)) {
            throw new Compiler\Exception(
                'Parsing error: cannot build AST, the trace is corrupted.',
                1
            );
        }

        return $this->_tree = $tree;
    }

    /**
     * Unfold trace.
     *
     * @return  mixed
     */
    protected function unfold()
    {
        while (0 < count($this->_todo)) {
            $rule = array_pop($this->_todo);

            if ($rule instanceof Rule\Ekzit) {
                $rule->setDepth($this->_depth);
                $this->_trace[] = $rule;

                if (false === $rule->isTransitional()) {
                    --$this->_depth;
                }
            } else {
                $ruleName = $rule->getRule();
                $next     = $rule->getData();
                $zeRule   = $this->_rules[$ruleName];
                $out      = $this->_parse($zeRule, $next);

                if (false === $out && false === $this->backtrack()) {
                    return null;
                }
            }
        }

        return true;
    }

    /**
     * Parse current rule.
     *
     * @param   \Hoa\Compiler\Llk\Rule  $zeRule    Current rule.
     * @param   int                     $next      Next rule index.
     * @return  bool
     */
    protected function _parse(Rule $zeRule, $next)
    {
        if ($zeRule instanceof Rule\Token) {
            $name = $this->_tokenSequence->current()['token'];

            if ($zeRule->getTokenName() !== $name) {
                return false;
            }

            $value = $this->_tokenSequence->current()['value'];

            if (0 <= $unification = $zeRule->getUnificationIndex()) {
                for ($skip = 0, $i = count($this->_trace) - 1; $i >= 0; --$i) {
                    $trace = $this->_trace[$i];

                    if ($trace instanceof Rule\Entry) {
                        if (false === $trace->isTransitional()) {
                            if ($trace->getDepth() <= $this->_depth) {
                                break;
                            }

                            --$skip;
                        }
                    } elseif ($trace instanceof Rule\Ekzit &&
                              false === $trace->isTransitional()) {
                        $skip += $trace->getDepth() > $this->_depth;
                    }

                    if (0 < $skip) {
                        continue;
                    }

                    if ($trace instanceof Rule\Token &&
                        $unification === $trace->getUnificationIndex() &&
                        $value       !== $trace->getValue()) {
                        return false;
                    }
                }
            }

            $namespace = $this->_tokenSequence->current()['namespace'];
            $zzeRule   = clone $zeRule;
            $zzeRule->setValue($value);
            $zzeRule->setNamespace($namespace);

            if (isset($this->_tokens[$namespace][$name])) {
                $zzeRule->setRepresentation($this->_tokens[$namespace][$name]);
            } else {
                foreach ($this->_tokens[$namespace] as $_name => $regex) {
                    if (false === $pos = strpos($_name, ':')) {
                        continue;
                    }

                    $_name = substr($_name, 0, $pos);

                    if ($_name === $name) {
                        break;
                    }
                }

                $zzeRule->setRepresentation($regex);
            }

            array_pop($this->_todo);
            $this->_trace[] = $zzeRule;
            $this->_tokenSequence->next();
            $this->_errorToken = $this->_tokenSequence->current();

            return true;
        } elseif ($zeRule instanceof Rule\Concatenation) {
            if (false === $zeRule->isTransitional()) {
                ++$this->_depth;
            }

            $this->_trace[] = new Rule\Entry(
                $zeRule->getName(),
                0,
                null,
                $this->_depth
            );
            $children = $zeRule->getChildren();

            for ($i = count($children) - 1; $i >= 0; --$i) {
                $nextRule      = $children[$i];
                $this->_todo[] = new Rule\Ekzit($nextRule, 0);
                $this->_todo[] = new Rule\Entry($nextRule, 0);
            }

            return true;
        } elseif ($zeRule instanceof Rule\Choice) {
            $children = $zeRule->getChildren();

            if ($next >= count($children)) {
                return false;
            }

            if (false === $zeRule->isTransitional()) {
                ++$this->_depth;
            }

            $this->_trace[] = new Rule\Entry(
                $zeRule->getName(),
                $next,
                $this->_todo,
                $this->_depth
            );
            $nextRule      = $children[$next];
            $this->_todo[] = new Rule\Ekzit($nextRule, 0);
            $this->_todo[] = new Rule\Entry($nextRule, 0);

            return true;
        } elseif ($zeRule instanceof Rule\Repetition) {
            $nextRule = $zeRule->getChildren();

            if (0 === $next) {
                $name = $zeRule->getName();
                $min  = $zeRule->getMin();

                if (false === $zeRule->isTransitional()) {
                    ++$this->_depth;
                }

                $this->_trace[] = new Rule\Entry(
                    $name,
                    $min,
                    null,
                    $this->_depth
                );
                array_pop($this->_todo);
                $this->_todo[]  = new Rule\Ekzit(
                    $name,
                    $min,
                    $this->_todo
                );

                for ($i = 0; $i < $min; ++$i) {
                    $this->_todo[] = new Rule\Ekzit($nextRule, 0);
                    $this->_todo[] = new Rule\Entry($nextRule, 0);
                }

                return true;
            } else {
                $max = $zeRule->getMax();

                if (-1 != $max && $next > $max) {
                    return false;
                }

                $this->_todo[] = new Rule\Ekzit(
                    $zeRule->getName(),
                    $next,
                    $this->_todo
                );
                $this->_todo[] = new Rule\Ekzit($nextRule, 0);
                $this->_todo[] = new Rule\Entry($nextRule, 0);

                return true;
            }
        }

        return false;
    }

    /**
     * Backtrack the trace.
     *
     * @return  bool
     */
    protected function backtrack()
    {
        $found = false;

        do {
            $last = array_pop($this->_trace);

            if ($last instanceof Rule\Entry) {
                $zeRule = $this->_rules[$last->getRule()];
                $found  = $zeRule instanceof Rule\Choice;
            } elseif ($last instanceof Rule\Ekzit) {
                $zeRule = $this->_rules[$last->getRule()];
                $found  = $zeRule instanceof Rule\Repetition;
            } elseif ($last instanceof Rule\Token) {
                $this->_tokenSequence->previous();

                if (false === $this->_tokenSequence->valid()) {
                    return false;
                }
            }
        } while (0 < count($this->_trace) && false === $found);

        if (false === $found) {
            return false;
        }

        $rule          = $last->getRule();
        $next          = $last->getData() + 1;
        $this->_depth  = $last->getDepth();
        $this->_todo   = $last->getTodo();
        $this->_todo[] = new Rule\Entry($rule, $next);

        return true;
    }

    /**
     * Build AST from trace.
     * Walk through the trace iteratively and recursively.
     *
     * @param   int      $i            Current trace index.
     * @param   array    &$children    Collected children.
     * @return  \Hoa\Compiler\Llk\TreeNode
     */
    protected function _buildTree($i = 0, &$children = [])
    {
        $max = count($this->_trace);

        while ($i < $max) {
            $trace = $this->_trace[$i];

            if ($trace instanceof Rule\Entry) {
                $ruleName  = $trace->getRule();
                $rule      = $this->_rules[$ruleName];
                $isRule    = false === $trace->isTransitional();
                $nextTrace = $this->_trace[$i + 1];
                $id        = $rule->getNodeId();

                // Optimization: Skip empty trace sequence.
                if ($nextTrace instanceof Rule\Ekzit &&
                    $ruleName == $nextTrace->getRule()) {
                    $i += 2;

                    continue;
                }

                if (true === $isRule) {
                    $children[] = $ruleName;
                }

                if (null !== $id) {
                    $children[] = [
                        'id'      => $id,
                        'options' => $rule->getNodeOptions()
                    ];
                }

                $i = $this->_buildTree($i + 1, $children);

                if (false === $isRule) {
                    continue;
                }

                $handle   = [];
                $cId      = null;
                $cOptions = [];

                do {
                    $pop = array_pop($children);

                    if (true === is_object($pop)) {
                        $handle[] = $pop;
                    } elseif (true === is_array($pop) && null === $cId) {
                        $cId      = $pop['id'];
                        $cOptions = $pop['options'];
                    } elseif ($ruleName == $pop) {
                        break;
                    }
                } while (null !== $pop);

                if (null === $cId) {
                    $cId      = $rule->getDefaultId();
                    $cOptions = $rule->getDefaultOptions();
                }

                if (null === $cId) {
                    for ($j = count($handle) - 1; $j >= 0; --$j) {
                        $children[] = $handle[$j];
                    }

                    continue;
                }

                if (true === in_array('M', $cOptions) &&
                    true === $this->mergeTree($children, $handle, $cId)) {
                    continue;
                }

                if (true === in_array('m', $cOptions) &&
                    true === $this->mergeTree($children, $handle, $cId, true)) {
                    continue;
                }

                $cTree = new TreeNode($id ?: $cId);

                foreach ($handle as $child) {
                    $child->setParent($cTree);
                    $cTree->prependChild($child);
                }

                $children[] = $cTree;
            } elseif ($trace instanceof Rule\Ekzit) {
                return $i + 1;
            } else {
                if (false === $trace->isKept()) {
                    ++$i;

                    continue;
                }

                $child = new TreeNode('token', [
                    'token'     => $trace->getTokenName(),
                    'value'     => $trace->getValue(),
                    'namespace' => $trace->getNamespace(),
                ]);
                $children[] = $child;
                ++$i;
            }
        }

        return $children[0];
    }

    /**
     * Try to merge directly children into an existing node.
     *
     * @param   array   &$children    Current children being gathering.
     * @param   array   &$handle      Children of the new node.
     * @param   string  $cId          Node ID.
     * @param   bool    $recursive    Whether we should merge recursively or
     *                                not.
     * @return  bool
     */
    protected function mergeTree(
        &$children,
        &$handle,
        $cId,
        $recursive = false
    ) {
        end($children);
        $last = current($children);

        if (!is_object($last)) {
            return false;
        }

        if ($cId !== $last->getId()) {
            return false;
        }

        if (true === $recursive) {
            foreach ($handle as $child) {
                $this->mergeTreeRecursive($last, $child);
            }

            return true;
        }

        foreach ($handle as $child) {
            $last->appendChild($child);
            $child->setParent($last);
        }

        return true;
    }

    /**
     * Merge recursively.
     * Please, see self::mergeTree() to know the context.
     *
     * @param   \Hoa\Compiler\Llk\TreeNode  $node       Node that receives.
     * @param   \Hoa\Compiler\Llk\TreeNode  $newNode    Node to merge.
     * @return  void
     */
    protected function mergeTreeRecursive(TreeNode $node, TreeNode $newNode)
    {
        $nNId = $newNode->getId();

        if ('token' === $nNId) {
            $node->appendChild($newNode);
            $newNode->setParent($node);

            return;
        }

        $children = $node->getChildren();
        end($children);
        $last     = current($children);

        if ($last->getId() !== $nNId) {
            $node->appendChild($newNode);
            $newNode->setParent($node);

            return;
        }

        foreach ($newNode->getChildren() as $child) {
            $this->mergeTreeRecursive($last, $child);
        }

        return;
    }

    /**
     * Get AST.
     *
     * @return  \Hoa\Compiler\Llk\TreeNode
     */
    public function getTree()
    {
        return $this->_tree;
    }

    /**
     * Get trace.
     *
     * @return  array
     */
    public function getTrace()
    {
        return $this->_trace;
    }

    /**
     * Get pragmas.
     *
     * @return  array
     */
    public function getPragmas()
    {
        return $this->_pragmas;
    }

    /**
     * Get tokens.
     *
     * @return  array
     */
    public function getTokens()
    {
        return $this->_tokens;
    }

    /**
     * Get the lexer iterator.
     *
     * @return  \Hoa\Iterator\Buffer
     */
    public function getTokenSequence()
    {
        return $this->_tokenSequence;
    }

    /**
     * Get rule by name.
     *
     * @param   string  $name    Rule name.
     * @return  \Hoa\Compiler\Llk\Rule
     */
    public function getRule($name)
    {
        if (!isset($this->_rules[$name])) {
            return null;
        }

        return $this->_rules[$name];
    }

    /**
     * Get rules.
     *
     * @return  array
     */
    public function getRules()
    {
        return $this->_rules;
    }

    /**
     * Get root rule.
     *
     * @return  string
     */
    public function getRootRule()
    {
        foreach ($this->_rules as $rule => $_) {
            if (!is_int($rule)) {
                break;
            }
        }

        return $rule;
    }
}
