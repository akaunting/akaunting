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

namespace Hoa\Math\Visitor;

use Hoa\Math;
use Hoa\Visitor;

/**
 * Class \Hoa\Math\Visitor\Arithmetic.
 *
 * Evaluate arithmetical expressions.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 *             Ivan Enderlin, Cédric Dugat.
 * @license    New BSD License
 */
class Arithmetic implements Visitor\Visit
{
    /**
     * Visitor context containing the list of supported functions, constants and variables
     *
     * @var \Hoa\Math\Context
     */
    protected $_context = null;

    /**
     * Initializes context.
     *
     */
    public function __construct()
    {
        $this->initializeContext();

        return;
    }

    /**
     * Set visitor's context
     *
     * @param   \Hoa\Math\Context $context
     * @return  \Hoa\Math\Context
     */
    public function setContext(Math\Context $context)
    {
        $old = $this->_context;

        $this->_context = $context;

        return $old;
    }

    /**
     * Get visitor's context
     *
     * @return  \Hoa\Math\Context
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * Visit an element.
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  float
     */
    public function visit(
        Visitor\Element $element,
        &$handle = null,
        $eldnah  = null
    ) {
        $type     = $element->getId();
        $children = $element->getChildren();

        if (null === $handle) {
            $handle = function ($x) {
                return $x;
            };
        }

        $acc = &$handle;

        switch ($type) {
            case '#function':
                $name      = array_shift($children)->accept($this, $_, $eldnah);
                $function  = $this->getFunction($name);
                $arguments = [];

                foreach ($children as $child) {
                    $child->accept($this, $_, $eldnah);
                    $arguments[] = $_();
                    unset($_);
                }

                $acc = function () use ($function, $arguments, $acc) {
                    return $acc($function->distributeArguments($arguments));
                };

                break;

            case '#negative':
                $children[0]->accept($this, $a, $eldnah);

                $acc = function () use ($a, $acc) {
                    return $acc(-$a());
                };

                break;

            case '#addition':
                $children[0]->accept($this, $a, $eldnah);

                $acc = function ($b) use ($a, $acc) {
                    return $acc($a() + $b);
                };

                $children[1]->accept($this, $acc, $eldnah);

                break;

            case '#substraction':
                $children[0]->accept($this, $a, $eldnah);

                $acc = function ($b) use ($a, $acc) {
                    return $acc($a()) - $b;
                };

                $children[1]->accept($this, $acc, $eldnah);

                break;

            case '#multiplication':
                $children[0]->accept($this, $a, $eldnah);

                $acc = function ($b) use ($a, $acc) {
                    return $acc($a() * $b);
                };

                $children[1]->accept($this, $acc, $eldnah);

                break;

            case '#division':
                $children[0]->accept($this, $a, $eldnah);
                $parent = $element->getParent();

                if (null  === $parent ||
                    $type === $parent->getId()) {
                    $acc = function ($b) use ($a, $acc) {
                        if (0.0 === $b) {
                            throw new \RuntimeException(
                                'Division by zero is not possible.'
                            );
                        }

                        return $acc($a()) / $b;
                    };
                } else {
                    if ('#fakegroup' !== $parent->getId()) {
                        $classname = get_class($element);
                        $group     = new $classname(
                            '#fakegroup',
                            null,
                            [$element],
                            $parent
                        );
                        $element->setParent($group);

                        $this->visit($group, $acc, $eldnah);

                        break;
                    } else {
                        $acc = function ($b) use ($a, $acc) {
                            if (0.0 === $b) {
                                throw new \RuntimeException(
                                    'Division by zero is not possible.'
                                );
                            }

                            return $acc($a() / $b);
                        };
                    }
                }

                $children[1]->accept($this, $acc, $eldnah);

                break;

            case '#fakegroup':
            case '#group':
                $children[0]->accept($this, $a, $eldnah);

                $acc = function () use ($a, $acc) {
                    return $acc($a());
                };

                break;

            case '#variable':
                $out = $this->getVariable($children[0]->getValueValue());

                $acc = function () use ($out, $acc) {
                    return $acc($out);
                };

                break;

            case 'token':
                $value = $element->getValueValue();
                $out   = null;

                if ('constant' === $element->getValueToken()) {
                    if (defined($value)) {
                        $out = constant($value);
                    } else {
                        $out = $this->getConstant($value);
                    }
                } elseif ('id' === $element->getValueToken()) {
                    return $value;
                } else {
                    $out = (float) $value;
                }

                $acc = function () use ($out, $acc) {
                    return $acc($out);
                };

                break;
        }

        if (null === $element->getParent()) {
            return $acc();
        }
    }

    /**
     * Get functions.
     *
     * @return  \ArrayObject
     */
    public function getFunctions()
    {
        return $this->_context->getFunctions();
    }

    /**
     * Get a function.
     *
     * @param   string  $name    Function name.
     * @return  \Hoa\Consistency\Xcallable
     * @throws  \Hoa\Math\Exception\UnknownFunction
     */
    public function getFunction($name)
    {
        return $this->_context->getFunction($name);
    }

    /**
     * Get constants.
     *
     * @return  \ArrayObject
     */
    public function getConstants()
    {
        return $this->_context->getConstants();
    }

    /**
     * Get a constant.
     *
     * @param   string  $name    Constant name.
     * @return  mixed
     * @throws  \Hoa\Math\Exception\UnknownFunction
     */
    public function getConstant($name)
    {
        return $this->_context->getConstant($name);
    }

    /**
     * Get variables.
     *
     * @return \ArrayObject
     */
    public function getVariables()
    {
        return $this->_context->getVariables();
    }

    /**
     * Get a variable.
     *
     * @param   string  $name    Variable name.
     * @return  callable
     * @throws  \Hoa\Math\Exception\UnknownVariable
     */
    public function getVariable($name)
    {
        return $this->_context->getVariable($name);
    }

    protected function initializeContext()
    {
        if (null === $this->_context) {
            $this->_context = new Math\Context();
        }

        return;
    }

    /**
     * Add a function.
     *
     * @param   string  $name        Function name.
     * @param   mixed   $callable    Callable.
     * @return  void
     */
    public function addFunction($name, $callable = null)
    {
        return $this->_context->addFunction($name, $callable);
    }

    /**
     * Add a constant.
     *
     * @param   string  $name     Constant name.
     * @param   mixed   $value    Value.
     * @return  void
     */
    public function addConstant($name, $value)
    {
        return $this->_context->addConstant($name, $value);
    }

    /**
     * Add a variable.
     *
     * @param   string    $name        Variable name.
     * @param   callable  $callable    Callable.
     * @return  void
     */
    public function addVariable($name, callable $callable)
    {
        return $this->_context->addVariable($name, $callable);
    }
}
