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

use Hoa\Consistency;

/**
 * Class \Hoa\Compiler\Llk\Rule.
 *
 * Rule parent.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Rule
{
    /**
     * Rule name.
     *
     * @var string
     */
    protected $_name           = null;

    /**
     * Rule's children. Can be an array of names or a single name.
     *
     * @var mixed
     */
    protected $_children       = null;

    /**
     * Node ID.
     *
     * @var string
     */
    protected $_nodeId         = null;

    /**
     * Node options.
     *
     * @var array
     */
    protected $_nodeOptions    = [];

    /**
     * Default ID.
     *
     * @var string
     */
    protected $_defaultId      = null;

    /**
     * Default options.
     *
     * @var array
     */
    protected $_defaultOptions = [];

    /**
     * For non-transitional rule: PP representation.
     *
     * @var string
     */
    protected $_pp             = null;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $_transitional   = true;



    /**
     * Constructor.
     *
     * @param   string  $name        Rule name.
     * @param   mixed   $children    Children.
     * @param   string  $nodeId      Node ID.
     */
    public function __construct($name, $children, $nodeId = null)
    {
        $this->setName($name);
        $this->setChildren($children);
        $this->setNodeId($nodeId);

        return;
    }

    /**
     * Set rule name.
     *
     * @param   string  $name    Rule name.
     * @return  string
     */
    public function setName($name)
    {
        $old         = $this->_name;
        $this->_name = $name;

        return $old;
    }

    /**
     * Get rule name.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set rule's children.
     *
     * @param   mixed  $children    Children.
     * @return  mixed
     */
    protected function setChildren($children)
    {
        $old             = $this->_children;
        $this->_children = $children;

        return $old;
    }

    /**
     * Get rule's children.
     *
     * @return  mixed
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * Set node ID.
     *
     * @param   string  $nodeId    Node ID.
     * @return  string
     */
    public function setNodeId($nodeId)
    {
        $old = $this->_nodeId;

        if (false !== $pos = strpos($nodeId, ':')) {
            $this->_nodeId      = substr($nodeId, 0, $pos);
            $this->_nodeOptions = str_split(substr($nodeId, $pos + 1));
        } else {
            $this->_nodeId      = $nodeId;
            $this->_nodeOptions = [];
        }

        return $old;
    }

    /**
     * Get node ID.
     *
     * @return  string
     */
    public function getNodeId()
    {
        return $this->_nodeId;
    }

    /**
     * Get node options.
     *
     * @retrun  array
     */
    public function getNodeOptions()
    {
        return $this->_nodeOptions;
    }

    /**
     * Set default ID.
     *
     * @param   string  $defaultId    Default ID.
     * @return  string
     */
    public function setDefaultId($defaultId)
    {
        $old = $this->_defaultId;

        if (false !== $pos = strpos($defaultId, ':')) {
            $this->_defaultId      = substr($defaultId, 0, $pos);
            $this->_defaultOptions = str_split(substr($defaultId, $pos + 1));
        } else {
            $this->_defaultId      = $defaultId;
            $this->_defaultOptions = [];
        }

        return $old;
    }

    /**
     * Get default ID.
     *
     * @return  string
     */
    public function getDefaultId()
    {
        return $this->_defaultId;
    }

    /**
     * Get default options.
     *
     * @return  array
     */
    public function getDefaultOptions()
    {
        return $this->_defaultOptions;
    }

    /**
     * Set PP representation of the rule.
     *
     * @param   string  $pp    PP representation.
     * @return  string
     */
    public function setPPRepresentation($pp)
    {
        $old                 = $this->_pp;
        $this->_pp           = $pp;
        $this->_transitional = false;

        return $old;
    }

    /**
     * Get PP representation of the rule.
     *
     * @return  string
     */
    public function getPPRepresentation()
    {
        return $this->_pp;
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

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Compiler\Llk\Rule\Rule');
