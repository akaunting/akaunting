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

namespace Hoa\Compiler;

/**
 * Define the __ constant, so useful in compiler :-).
 */
!defined('GO') and define('GO', 'GO');
!defined('__') and define('__', '__');

/**
 * Class \Hoa\Compiler\Ll1.
 *
 * Provide an abstract LL(1) compiler, based on sub-automata and stacks.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Ll1
{
    /**
     * Initial line.
     * If we try to compile a code inside another code, the initial line would
     * not probably be 0.
     *
     * @var int
     */
    protected $_initialLine         = 0;

    /**
     * Tokens to skip (will be totally skip, no way to get it).
     * Tokens' rules could be apply here (i.e. normal and special tokens are
     * understood).
     * Example:
     *     [
     *         '#\s+',           // white spaces
     *         '#//.*',          // inline comment
     *         '#/\*(.|\n)*\*\/' // block comment
     *     ]
     *
     * @var array
     */
    protected $_skip                = [];

    /**
     * Tokens.
     * A token should be:
     *     * simple, it means just a single char, e.g. ':';
     *     * special, strings and/or a regular expressions, and must begin with
     *       a sharp (#), e.g. '#foobar', '#[a-zA-Z]' or '#foo?bar'.
     * Note: if we want the token #, we should write '##'.
     * PCRE expressions are fully-supported.
     * We got an array of arrays because of sub-automata, one sub-array per
     * sub-automaton.
     * Example:
     *     [
     *         [
     *             '{'  // open brack
     *         ],
     *         [
     *             '"',            // quote
     *             ':',            // semi-colon
     *             ',',            // comma
     *             '{',            // open bracket
     *             '}'             // close bracket
     *         ],
     *         [
     *             '#[a-z_\ \n]+", // id/string
     *             '"'             // quote
     *         ]
     *     ]
     *
     * @var array
     */
    protected $_tokens              = [];

    /**
     * States.
     * We got an array of arrays because of sub-automata, one sub-array per
     * sub-automaton.
     * Example:
     *     [
     *         [
     *              __ , // error
     *             'GO', // start
     *             'OK'  // terminal
     *         ],
     *         [
     *              __ , // error
     *             'GO', // start
     *             'KE', // key
     *             'CO', // colon
     *             'VA', // value
     *             'BL', // block
     *             'OK'  // terminal
     *         ],
     *         [
     *              __ , // error
     *             'GO', // start
     *             'ST', // string
     *             'OK'  // terminal
     *         ]
     *     ]
     *
     * Note: the constant GO or the string 'GO' must be used to represent the
     *       initial state.
     * Note: the constant __ or the string '__' must be used to represent the
     *       null/unrecognized/error state.
     *
     * @var array
     */
    protected $_states              = [];

    /**
     * Terminal states (defined in the states set).
     * We got an array of arrays because of sub-automata, one sub-array per
     * sub-automaton.
     * Example:
     *     [
     *         ['OK'],
     *         ['OK'],
     *         ['OK']
     *     ]
     *
     * @var array
     */
    protected $_terminal            = [];

    /**
     * Transitions table.
     * It's actually a matrix, such as: TT(TOKENS × STATES).
     * We got an array of arrays because of sub-automata, one sub-array per
     * sub-automaton.
     * Example:
     *     [
     *        [
     *                   {
     *             __  [ __ ],
     *             GO  ['OK'],
     *             OK  [ __ ],
     *         ),
     *         [
     *                   "     :     ,     {     }
     *             __  [ __ ,  __ ,  __ ,  __ ,  __ ],
     *             GO  ['KE',  __ ,  __ ,  __ , 'OK'],
     *             KE  [ __ , 'CO',  __ ,  __ ,  __ ],
     *             CO  ['VA',  __ ,  __ , 'BL',  __ ],
     *             VA  [ __ ,  __ , 'GO',  __ , 'OK'],
     *             BL  [ __ ,  __ , 'GO',  __ , 'OK'],
     *             OK  [ __ ,  __ ,  __ ,  __ ,  __ ]
     *         ],
     *         [
     *                   id    "
     *             __  [ __ ,  __ ],
     *             GO  ['ST', 'OK'],
     *             ST  [ __ , 'OK'],
     *             OK  [ __ ,  __ ]
     *         ]
     *     )
     *
     * Note: tokens and states should be declared in the strict same order as
     *       defined previously.
     *
     * @var array
     */
    protected $_transitions         = [];

    /**
     * Actions table.
     * It's actually a matrix, such as: AT(TOKENS × STATES).
     * We got an array of arrays because of sub-automata, one sub-array per
     * sub-automaton.
     * Example:
     *     [
     *         [
     *                   {
     *             __  [ 0 ],
     *             GO  [ 0 ],
     *             OK  [ 2 ],
     *         ],
     *         [
     *                   "   :    ,    {    }
     *             __  [ 0,  0 ,  0 ,  0 ,  0 ],
     *             GO  [ 0,  0 ,  0 ,  0 , 'd'],
     *             KE  [ 3, 'k',  0 ,  0 ,  0 ],
     *             CO  [ 0,  0 ,  0 , 'u',  0 ],
     *             VA  [ 3,  0 , 'v',  0 , 'x'],
     *             BL  [ 0,  0 ,  0 ,  2 , 'd'],
     *             OK  [ 0,  0 ,  0 ,  0 ,  0 ]
     *         ],
     *         [
     *                   id  "
     *             __  [ 0, 0 ],
     *             GO  [-1, 0 ],
     *             ST  [ 0, 0 ],
     *             OK  [ 0, 0 ]
     *         ]
     *     ]
     *
     * AT is filled with integer or char n.
     * If n is a char, it means an action.
     * If n < 0, it means a special action.
     * If n = 0, it means not action.
     * If n > 0, it means a link to a sub-automata (sub-automata index + 1).
     *
     * When we write our consume() method, it's just a simple switch receiving
     * an action. It receives only character. It's like a “goto” in our
     * compiler, and allows us to execute code when skiming through the graph.
     *
     * Negative/special actions are used to auto-fill or empty buffers.
     * E.g: -1 will fill the buffer 0, -2 will empty the buffer 0,
     *      -3 will fill the buffer 1, -4 will empty the buffer 1,
     *      -5 will fill the buffer 2, -6 will empty the buffer 2 etc.
     * A formula appears:
     *      y = |x|
     *      fill  buffer (x - 2) / 2 if x & 1 = 1
     *      empty buffer (x - 1) / 2 if x & 1 = 0
     *
     * Positive/link actions are used to make an epsilon-transition (or a link)
     * between two sub-automata.
     * Sub-automata are indexed from 0, but our links must be index + 1. Example
     * given: the sub-automata 0 in our example has a link to the sub-automata 1
     * through OK[{] = 2. Take attention to this :-).
     * And another thing which must be carefully studying is the place of the
     * link. For example, with our sub-automata 1 (the big one), we have an
     * epsilon-transition to the sub-automata 2 through VA["] = 3. It means:
     * when we arrived in the state VA from the token ", we go in the
     * sub-automata 3 (the 2nd one actually). And when the linked sub-automata
     * has finished, we are back in our state and continue our parsing. Take
     * care of this :-).
     *
     * Finally, it is possible to combine positive and char action, separated by
     a comma. Thus: 7,f is equivalent to make an epsilon-transition to the
     * automata 7, then consume the action f.
     *
     * @var array
     */
    protected $_actions             = [];

    /**
     * Names of automata.
     */
    protected $_names               = [];

    /**
     * Recursive stack.
     *
     * @var array
     */
    private $_stack                 = [];

    /**
     * Buffers.
     *
     * @var array
     */
    protected $buffers              = [];

    /**
     * Current token's line.
     *
     * @var int
     */
    protected $line                 = 0;

    /**
     * Current token's column.
     *
     * @var int
     */
    protected $column               = 0;

    /**
     * Cache compiling result.
     *
     * @var array
     */
    protected static $_cache        = [];

    /**
     * Whether cache is enabled or not.
     *
     * @var bool
     */
    protected static $_cacheEnabled = true;



    /**
     * Singleton, and set parameters.
     *
     * @param   array   $skip           Skip.
     * @param   array   $tokens         Tokens.
     * @param   array   $states         States.
     * @param   array   $terminal       Terminal states.
     * @param   array   $transitions    Transitions table.
     * @param   array   $actions        Actions table.
     * @param   array   $names          Names of automata.
     */
    public function __construct(
        array $skip,
        array $tokens,
        array $states,
        array $terminal,
        array $transitions,
        array $actions,
        array $names = []
    ) {
        $this->setSkip($skip);
        $this->setTokens($tokens);
        $this->setStates($states);
        $this->setTerminal($terminal);
        $this->setTransitions($transitions);
        $this->setActions($actions);
        $this->setNames($names);

        return;
    }

    /**
     * Compile a source code.
     *
     * @param   string  $in    Source code.
     * @return  void
     * @throws  \Hoa\Compiler\Exception\FinalStateHasNotBeenReached
     * @throws  \Hoa\Compiler\Exception\IllegalToken
     */
    public function compile($in)
    {
        $cacheId = md5($in);

        if (true === self::$_cacheEnabled &&
            true === array_key_exists($cacheId, self::$_cache)) {
            return self::$_cache[$cacheId];
        }

        $d             = 0;
        $c             = 0; // current automata.
        $_skip         = array_flip($this->_skip);
        $_tokens       = array_flip($this->_tokens[$c]);
        $_states       = array_flip($this->_states[$c]);
        $_actions      = [$c => 0];

        $nextChar      = null;
        $nextToken     = 0;
        $nextState     = $_states['GO'];
        $nextAction    = $_states['GO'];

        $this->line    = $this->getInitialLine();
        $this->column  = 0;

        $this->buffers = [];

        $line          = $this->line;
        $column        = $this->column;

        $this->pre($in);

        for ($i = 0, $max = strlen($in); $i <= $max; $i++) {

            //echo "\n---\n\n";

            // End of parsing (not automata).
            if ($i == $max) {
                while ($c > 0 &&
                       in_array($this->_states[$c][$nextState], $this->_terminal[$c])) {
                    list($c, $nextState, ) = array_pop($this->_stack);
                }

                if (in_array($this->_states[$c][$nextState], $this->_terminal[$c]) &&
                    0    === $c &&
                    true === $this->end()) {

                    //echo '*********** END REACHED **********' . "\n";

                    if (true === self::$_cacheEnabled) {
                        self::$_cache[$cacheId] = $this->getResult();
                    }

                    return true;
                }

                throw new Exception\FinalStateHasNotBeenReached(
                    'End of code has been reached but not correctly; ' .
                    'maybe your program is not complete?',
                    0
                );
            }

            $nextChar = $in[$i];

            // Skip.
            if (isset($_skip[$nextChar])) {
                if ("\n" === $nextChar) {
                    $line++;
                    $column = 0;
                } else {
                    $column++;
                }

                continue;
            } else {
                $continue = false;
                $handle   = substr($in, $i);

                foreach ($_skip as $sk => $e) {
                    if ($sk[0] != '#') {
                        continue;
                    }

                    $sk = str_replace('#', '\#', substr($sk, 1));

                    if (0 != preg_match('#^(' . $sk . ')#u', $handle, $match)) {
                        $strlen = strlen($match[1]);

                        if ($strlen > 0) {
                            if (false !== $offset = strrpos($match[1], "\n")) {
                                $column  = $strlen - $offset - 1;
                            } else {
                                $column += $strlen;
                            }

                            $line     += substr_count($match[1], "\n");
                            $i        += $strlen - 1;
                            $continue  = true;

                            break;
                        }
                    }
                }

                if (true === $continue) {
                    continue;
                }
            }

            // Epsilon-transition.
            $epsilon = false;
            while (array_key_exists($nextToken, $this->_actions[$c][$nextState]) &&
                     (
                        (
                            is_array($this->_actions[$c][$nextState][$nextToken]) &&
                            0 < $foo = $this->_actions[$c][$nextState][$nextToken][0]
                        ) ||
                        (
                            is_int($this->_actions[$c][$nextState][$nextToken]) &&
                            0 < $foo = $this->_actions[$c][$nextState][$nextToken]
                        )
                     )
                  ) {
                $epsilon = true;

                if ($_actions[$c] == 0) {

                    //echo '*** Change automata (up to ' . ($foo - 1) . ')' . "\n";

                    $this->_stack[$d] = [$c, $nextState, $nextToken];
                    end($this->_stack);

                    $c            = $foo - 1;
                    $_tokens      = array_flip($this->_tokens[$c]);
                    $_states      = array_flip($this->_states[$c]);

                    $nextState    = $_states['GO'];
                    $nextAction   = $_states['GO'];
                    $nextToken    = 0;

                    $_actions[$c] = 0;

                    $d++;
                } elseif ($_actions[$c] == 2) {
                    $_actions[$c] = 0;

                    break;
                }
            }

            if (true === $epsilon) {
                $epsilon   = false;
                $nextToken = false;
            }

            // Token.
            if (isset($_tokens[$nextChar])) {
                $token      = $nextChar;
                $nextToken  = $_tokens[$token];

                if ("\n" === $nextChar) {
                    $line++;
                    $column = 0;
                } else {
                    $column++;
                }
            } else {
                $nextToken = false;
                $handle    = substr($in, $i);

                foreach ($_tokens as $token => $e) {
                    if ('#' !== $token[0]) {
                        continue;
                    }

                    $ntoken = str_replace('#', '\#', substr($token, 1));

                    if (0 != preg_match('#^(' . $ntoken . ')#u', $handle, $match)) {
                        $strlen = strlen($match[1]);

                        if ($strlen > 0) {
                            if (false !== $offset = strrpos($match[1], "\n")) {
                                $column  = $strlen - $offset - 1;
                            } else {
                                $column += $strlen;
                            }

                            $nextChar   = $match[1];
                            $nextToken  = $e;
                            $i         += $strlen - 1;
                            $line      += substr_count($match[1], "\n");

                            break;
                        }
                    }
                }
            }

            /*
            echo '>>> Automata   ' . $c . "\n" .
                 '>>> Next state ' . $nextState . "\n" .
                 '>>> Token      ' . $token . "\n" .
                 '>>> Next char  ' . $nextChar . "\n";
            */

            // Got it!
            if (false !== $nextToken) {
                if (is_array($this->_actions[$c][$nextState][$nextToken])) {
                    $nextAction = $this->_actions[$c][$nextState][$nextToken][1];
                } else {
                    $nextAction = $this->_actions[$c][$nextState][$nextToken];
                }
                $nextState      = $_states[$this->_transitions[$c][$nextState][$nextToken]];
            }

            // Oh :-(.
            if (false === $nextToken || $nextState === $_states['__']) {
                $pop = array_pop($this->_stack);
                $d--;

                // Go back to a parent automata.
                if ((in_array($this->_states[$c][$nextState], $this->_terminal[$c]) &&
                     null !== $pop) ||
                    ($nextState === $_states['__'] &&
                     null !== $pop)) {

                    //echo '!!! Change automata (down)' . "\n";

                    list($c, $nextState, $nextToken) = $pop;

                    $_actions[$c]  = 2;

                    $i            -= strlen($nextChar);
                    $_tokens       = array_flip($this->_tokens[$c]);
                    $_states       = array_flip($this->_states[$c]);

                    /*
                    echo '!!! Automata   ' . $c . "\n" .
                         '!!! Next state ' . $nextState . "\n";
                    */

                    continue;
                }

                $error = explode("\n", $in);
                $error = $error[$this->line];

                throw new Exception\IllegalToken(
                    'Illegal token at line ' . ($this->line + 1) . ' and column ' .
                    ($this->column + 1) . "\n" . $error . "\n" .
                    str_repeat(' ', $this->column) . '↑',
                    1,
                    [],
                    $this->line + 1, $this->column + 1
                );
            }

            $this->line   = $line;
            $this->column = $column;

            //echo '<<< Next state ' . $nextState . "\n";

            $this->buffers[-1] = $nextChar;

            // Special actions.
            if ($nextAction < 0) {
                $buffer = abs($nextAction);

                if (($buffer & 1) == 0) {
                    $this->buffers[($buffer - 2) / 2] = null;
                } else {
                    $buffer = ($buffer - 1) / 2;

                    if (!(isset($this->buffers[$buffer]))) {
                        $this->buffers[$buffer] = null;
                    }

                    $this->buffers[$buffer] .= $nextChar;
                }

                continue;
            }

            if (0 !== $nextAction) {
                $this->consume($nextAction);
            }
        }

        return;
    }

    /**
     * Consume actions.
     * Please, see the actions table definition to learn more.
     *
     * @param   int  $action    Action.
     * @return  void
     */
    abstract protected function consume($action);

    /**
     * Compute source code before compiling it.
     *
     * @param   string  &$in    Source code.
     * @return  void
     */
    protected function pre(&$in)
    {
        return;
    }

    /**
     * Verify compiler state when ending the source code.
     *
     * @return  bool
     */
    protected function end()
    {
        return true;
    }

    /**
     * Get the result of the compiling.
     *
     * @return  mixed
     */
    abstract public function getResult();

    /**
     * Set initial line.
     *
     * @param   int     $line    Initial line.
     * @return  int
     */
    public function setInitialLine($line)
    {
        $old                = $this->_initialLine;
        $this->_initialLine = $line;

        return $old;
    }

    /**
     * Set tokens to skip.
     *
     * @param   array   $skip    Skip.
     * @return  array
     */
    public function setSkip(array $skip)
    {
        $old         = $this->_skip;
        $this->_skip = $skip;

        return $old;
    }


    /**
     * Set tokens.
     *
     * @param   array   $tokens    Tokens.
     * @return  array
     */
    public function setTokens(array $tokens)
    {
        $old           = $this->_tokens;
        $this->_tokens = $tokens;

        return $old;
    }

    /**
     * Set states.
     *
     * @param   array   $states    States.
     * @return  array
     */
    public function setStates(array $states)
    {
        $old           = $this->_states;
        $this->_states = $states;

        return $old;
    }

    /**
     * Set terminal states.
     *
     * @param   array   $terminal    Terminal states.
     * @return  array
     */
    public function setTerminal(array $terminal)
    {
        $old             = $this->_terminal;
        $this->_terminal = $terminal;

        return $old;
    }

    /**
     * Set transitions table.
     *
     * @param   array   $transitions    Transitions table.
     * @return  array
     */
    public function setTransitions(array $transitions)
    {
        $old                = $this->_transitions;
        $this->_transitions = $transitions;

        return $old;
    }

    /**
     * Set actions table.
     *
     * @param   array   $actions    Actions table.
     * @return  array
     */
    public function setActions(array $actions)
    {
        foreach ($actions as $e => $automata) {
            foreach ($automata as $i => $state) {
                foreach ($state as $j => $token) {
                    if (0 != preg_match('#^(\d+),(.*)$#', $token, $matches)) {
                        $actions[$e][$i][$j] = [(int) $matches[1], $matches[2]];
                    }
                }
            }
        }

        $old            = $this->_actions;
        $this->_actions = $actions;

        return $old;
    }

    /**
     * Set names of automata.
     *
     * @param   array   $names    Names of automata.
     * @return  array
     */
    public function setNames(array $names)
    {
        $old          = $this->_names;
        $this->_names = $names;

        return $old;
    }

    /**
     * Get initial line.
     *
     * @return  int
     */
    public function getInitialLine()
    {
        return $this->_initialLine;
    }

    /**
     * Get skip tokens.
     *
     * @return  array
     */
    public function getSkip()
    {
        return $this->_skip;
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
     * Get states.
     *
     * @return  array
     */
    public function getStates()
    {
        return $this->_states;
    }

    /**
     * Get terminal states.
     *
     * @return  array
     */
    public function getTerminal()
    {
        return $this->_terminal;
    }

    /**
     * Get transitions table.
     *
     * @return  array
     */
    public function getTransitions()
    {
        return $this->_transitions;
    }

    /**
     * Get actions table.
     *
     * @return  array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * Get names of automata.
     *
     * @return  array
     */
    public function getNames()
    {
        return $this->_names;
    }

    /**
     * Enable cache
     *
     * @return  bool
     */
    public static function enableCache()
    {
        $old                 = self::$_cacheEnabled;
        self::$_cacheEnabled = true;

        return $old;
    }

    /**
     * Disable cache
     *
     * @return  bool
     */
    public static function disableCache()
    {
        $old                 = self::$_cacheEnabled;
        self::$_cacheEnabled = false;

        return $old;
    }

    /**
     * Transform automatas into DOT language.
     *
     * @return  void
     */
    public function __toString()
    {
        $out =
            'digraph ' . str_replace('\\', '', get_class($this)) . ' {' .
            "\n" .
            '    rankdir=LR;' . "\n" .
            '    label="Automata of ' .
            str_replace('\\', '\\\\', get_class($this)) . '";';

        $transitions = array_reverse($this->_transitions, true);

        foreach ($transitions as $e => $automata) {
            $out .=
                "\n\n" . '    subgraph cluster_' . $e . ' {' . "\n" .
                '        label="Automata #' . $e .
                (isset($this->_names[$e])
                    ? ' (' . str_replace('"', '\\"', $this->_names[$e]) . ')'
                    : '') .
                '";' . "\n";

            if (!empty($this->_terminal[$e])) {
                $out .=
                    '        node[shape=doublecircle] "' . $e . '_' .
                    implode('" "' . $e . '_', $this->_terminal[$e]) . '";' . "\n";
            }

            $out .= '        node[shape=circle];' . "\n";

            foreach ($this->_states[$e] as $i => $state) {
                $name  = [];
                $label = $state;

                if (__ != $state) {
                    foreach ($this->_transitions[$e][$i] as $j => $foo) {
                        $ep = $this->_actions[$e][$i][$j];

                        if (is_array($ep)) {
                            $ep = $ep[0];
                        }

                        if (is_int($ep)) {
                            $ep--;

                            if (0 < $ep && !isset($name[$ep])) {
                                $name[$ep] = $ep;
                            }
                        }
                    }

                    if (!empty($name)) {
                        $label .= ' (' . implode(', ', $name) . ')';
                    }

                    $out .=
                        '        "' . $e . '_' . $state . '" ' .
                        '[label="' . $label . '"];' . "\n";
                }
            }

            foreach ($automata as $i => $transition) {
                $transition = array_reverse($transition, true);

                foreach ($transition as $j => $state) {
                    if (__ != $this->_states[$e][$i]
                       && __ != $state) {
                        $label = str_replace('\\', '\\\\', $this->_tokens[$e][$j]);
                        $label = str_replace('"', '\\"', $label);

                        if ('#' === $label[0]) {
                            $label = substr($label, 1);
                        }

                        $out .=
                            '        "' . $e . '_' . $this->_states[$e][$i] .
                            '" -> "' . $e . '_' . $state . '"' .
                            ' [label="' . $label . '"];' . "\n";
                    }
                }
            }

            $out .=
                '        node[shape=point,label=""] "' . $e . '_";' . "\n" .
                '        "' . $e . '_" -> "' . $e . '_GO";' . "\n" .
                '    }';
        }

        $out .= "\n" . '}' . "\n";

        return $out;
    }
}
