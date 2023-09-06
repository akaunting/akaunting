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

namespace Hoa\Stream;

/**
 * Class \Hoa\Stream\Context.
 *
 * Make a multiton of stream contexts.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context
{
    /**
     * Context ID.
     *
     * @var string
     */
    protected $_id               = null;

    /**
     * Multiton.
     *
     * @var array
     */
    protected static $_instances = [];



    /**
     * Construct a context.
     *
     */
    protected function __construct($id)
    {
        $this->_id      = $id;
        $this->_context = stream_context_create();

        return;
    }

    /**
     * Multiton.
     *
     * @param   string  $id    ID.
     * @return  \Hoa\Stream\Context
     * @throws  \Hoa\Stream\Exception
     */
    public static function getInstance($id)
    {
        if (empty($id)) {
            throw new Exception('Context ID must not be null.', 0);
        }

        if (false === static::contextExists($id)) {
            static::$_instances[$id] = new static($id);
        }

        return static::$_instances[$id];
    }

    /**
     * Get context ID.
     *
     * @return  string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Check if a context exists.
     *
     * @param   string  $id    ID.
     * @return  bool
     */
    public static function contextExists($id)
    {
        return array_key_exists($id, static::$_instances);
    }

    /**
     * Set options.
     * Please, see http://php.net/context.
     *
     * @param   array   $options    Options.
     * @return  bool
     */
    public function setOptions(array $options)
    {
        return stream_context_set_option($this->getContext(), $options);
    }

    /**
     * Set parameters.
     * Please, see http://php.net/context.params.
     *
     * @param   array   $parameters    Parameters.
     * @return  bool
     */
    public function setParameters(array $parameters)
    {
        return stream_context_set_params($this->getContext(), $parameters);
    }

    /**
     * Get options.
     *
     * @return  array
     */
    public function getOptions()
    {
        return stream_context_get_options($this->getContext());
    }

    /**
     * Get parameters.
     * .
     * @return  array
     */
    public function getParameters()
    {
        return stream_context_get_params($this->getContext());
    }

    /**
     * Get context as a resource.
     *
     * @return  resource
     */
    public function getContext()
    {
        return $this->_context;
    }
}
