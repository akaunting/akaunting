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

namespace Hoa\Compiler\Llk\Rule;

/**
 * Class \Hoa\Compiler\Llk\Rule\Invocation.
 *
 * Parent of entry and ekzit rules.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Invocation
{
    /**
     * Rule.
     *
     * @var string
     */
    protected $_rule         = null;

    /**
     * Data.
     *
     * @var mixed
     */
    protected $_data         = null;

    /**
     * Piece of todo sequence.
     *
     * @var array
     */
    protected $_todo         = null;

    /**
     * Depth in the trace.
     *
     * @var int
     */
    protected $_depth        = -1;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $_transitional = false;



    /**
     * Constructor.
     *
     * @param   string  $rule     Rule name.
     * @param   mixed   $data     Data.
     * @param   array   $todo     Todo.
     * @param   int     $depth    Depth.
     */
    public function __construct(
        $rule,
        $data,
        array $todo = null,
        $depth      = -1
    ) {
        $this->_rule         = $rule;
        $this->_data         = $data;
        $this->_todo         = $todo;
        $this->_depth        = $depth;
        $this->_transitional = is_int($rule);

        return;
    }

    /**
     * Get rule name.
     *
     * @return  string
     */
    public function getRule()
    {
        return $this->_rule;
    }

    /**
     * Get data.
     *
     * @return  mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Get todo sequence.
     *
     * @return  array
     */
    public function getTodo()
    {
        return $this->_todo;
    }

    /**
     * Set depth in trace.
     *
     * @param   int  $depth    Depth.
     * @return  int
     */
    public function setDepth($depth)
    {
        $old          = $this->_depth;
        $this->_depth = $depth;

        return $old;
    }

    /**
     * Get depth in trace.
     *
     * @return  int
     */
    public function getDepth()
    {
        return $this->_depth;
    }

    /**
     * Check whether the rule is transitional or not.
     *
     * @return  bool
     */
    public function isTransitional()
    {
        return $this->_transitional;
    }
}
