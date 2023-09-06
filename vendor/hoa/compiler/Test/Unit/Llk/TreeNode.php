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

use Hoa\Compiler\Llk\TreeNode as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Compiler\Test\Unit\Llk\TreeNode.
 *
 * Test suite of the tree node.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class TreeNode extends Test\Unit\Suite
{
    public function case_is_a_visitor()
    {
        $this
            ->when($node = new SUT('foo'))
            ->then
                ->object($node)
                    ->isInstanceOf(Visitor\Element::class);
    }

    public function case_constructor()
    {
        $this
            ->given($id = 'foo')
            ->when($node = new SUT($id))
            ->then
                ->string($node->getId())
                    ->isEqualTo($id)
                ->variable($node->getValue())
                    ->isNull()
                ->integer($node->getChildrenNumber())
                    ->isEqualTo(0)
                ->array($node->getChildren())
                    ->isEmpty()
                ->variable($node->getParent())
                    ->isNull();
    }

    public function case_constructor_with_a_value()
    {
        $this
            ->given(
                $id    = 'foo',
                $value = ['bar']
            )
            ->when($node = new SUT($id, $value))
            ->then
                ->string($node->getId())
                    ->isEqualTo($id)
                ->array($node->getValue())
                    ->isEqualTo($value)
                ->integer($node->getChildrenNumber())
                    ->isEqualTo(0)
                ->array($node->getChildren())
                    ->isEmpty()
                ->variable($node->getParent())
                    ->isNull();
    }

    public function case_constructor_with_a_value_and_children()
    {
        $this
            ->given(
                $id       = 'foo',
                $value    = ['bar'],
                $children = [new SUT('baz'), new SUT('qux')]
            )
            ->when($node = new SUT($id, $value, $children))
            ->then
                ->string($node->getId())
                    ->isEqualTo($id)
                ->array($node->getValue())
                    ->isEqualTo($value)
                ->integer($node->getChildrenNumber())
                    ->isEqualTo(2)
                ->array($node->getChildren())
                    ->isEqualTo($children)
                ->variable($node->getParent())
                    ->isNull();
    }

    public function case_constructor_with_a_value_and_children_and_a_parent()
    {
        $this
            ->given(
                $id       = 'foo',
                $value    = ['bar'],
                $children = [new SUT('baz'), new SUT('qux')],
                $parent   = new SUT('root')
            )
            ->when($node = new SUT($id, $value, $children, $parent))
            ->then
                ->string($node->getId())
                    ->isEqualTo($id)
                ->array($node->getValue())
                    ->isEqualTo($value)
                ->integer($node->getChildrenNumber())
                    ->isEqualTo(2)
                ->array($node->getChildren())
                    ->isEqualTo($children)
                ->object($node->getParent())
                    ->isIdenticalTo($parent);
    }

    public function case_set_id()
    {
        $this
            ->given($node = new SUT('foo'))
            ->when($result = $node->setId('bar'))
            ->then
                ->string($result)
                    ->isEqualTo('foo');
    }

    public function case_get_id()
    {
        $this
            ->given(
                $node = new SUT('foo'),
                $node->setId('bar')
            )
            ->when($result = $node->getId())
            ->then
                ->string($result)
                    ->isEqualTo('bar');
    }

    public function case_set_value()
    {
        $this
            ->given($node = new SUT('foo', ['bar']))
            ->when($result = $node->setValue(['baz']))
            ->then
                ->array($result)
                    ->isEqualTo(['bar']);
    }

    public function case_get_value()
    {
        $this
            ->given(
                $node = new SUT('foo', ['bar']),
                $node->setValue(['baz'])
            )
            ->when($result = $node->getValue())
            ->then
                ->array($result)
                    ->isEqualTo(['baz']);
    }

    public function case_get_value_token()
    {
        $this
            ->given($node = new SUT('foo', ['token' => 'bar']))
            ->when($result = $node->getValueToken())
            ->then
                ->string($result)
                    ->isEqualTo('bar');
    }

    public function case_get_value_token_undefined()
    {
        $this
            ->given($node = new SUT('foo', ['bar']))
            ->when($result = $node->getValueToken())
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_value_value()
    {
        $this
            ->given($node = new SUT('foo', ['value' => 'bar']))
            ->when($result = $node->getValueValue())
            ->then
                ->string($result)
                    ->isEqualTo('bar');
    }

    public function case_get_value_value_undefined()
    {
        $this
            ->given($node = new SUT('foo', ['bar']))
            ->when($result = $node->getValueValue())
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_is_token()
    {
        $this
            ->given($node = new SUT('foo', ['bar']))
            ->when($result = $node->isToken())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_not_token()
    {
        $this
            ->given($node = new SUT('foo'))
            ->when($result = $node->isToken())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_prepend_child()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $node   = new SUT('foo', ['bar'], [$childA])
            )
            ->when($result = $node->prependChild($childB))
            ->then
                ->object($result)
                    ->isIdenticalTo($node)
                ->integer($result->getChildrenNumber())
                    ->isEqualTo(2)
                ->array($result->getChildren())
                    ->isEqualTo([$childB, $childA]);
    }

    public function case_append_child()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $node   = new SUT('foo', ['bar'], [$childA])
            )
            ->when($result = $node->appendChild($childB))
            ->then
                ->object($result)
                    ->isIdenticalTo($node)
                ->integer($result->getChildrenNumber())
                    ->isEqualTo(2)
                ->array($result->getChildren())
                    ->isEqualTo([$childA, $childB]);
    }

    public function case_set_children()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $childC = new SUT('hello'),
                $node   = new SUT('foo', ['bar'], [$childA])
            )
            ->when($result = $node->setChildren([$childB, $childC]))
            ->then
                ->array($result)
                    ->isEqualTo([$childA])
                ->integer($node->getChildrenNumber())
                    ->isEqualTo(2)
                ->array($node->getChildren())
                    ->isEqualTo([$childB, $childC]);
    }

    public function case_get_child()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $node   = new SUT('foo', ['bar'], [$childA, $childB])
            )
            ->when($result = $node->getChild(0))
            ->then
                ->object($result)
                    ->isIdenticalTo($childA)

            ->when($result = $node->getChild(1))
            ->then
                ->object($result)
                    ->isIdenticalTo($childB);
    }

    public function case_get_child_undefined()
    {
        $this
            ->given(
                $node   = new SUT('foo', ['bar'])
            )
            ->when($result = $node->getChild(0))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_children()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $node   = new SUT('foo', ['bar'], [$childA, $childB])
            )
            ->when($result = $node->getChildren())
            ->then
                ->array($result)
                    ->isEqualTo([$childA, $childB]);
    }

    public function case_get_children_number()
    {
        $this
            ->given(
                $childA = new SUT('baz'),
                $childB = new SUT('qux'),
                $node   = new SUT('foo', ['bar'])
            )
            ->when($result = $node->getChildrenNumber())
            ->then
                ->integer($result)
                    ->isEqualTo(0)

            ->when(
                $node->setChildren([$childA, $childB]),
                $result = $node->getChildrenNumber()
            )
            ->then
                ->integer($result)
                    ->isEqualTo(2);
    }

    public function case_child_exists()
    {
        $this
            ->given($node = new SUT('foo', ['bar'], [new SUT('baz')]))
            ->when($result = $node->childExists(0))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_child_does_not_exist()
    {
        $this
            ->given($node = new SUT('foo', ['bar']))
            ->when($result = $node->childExists(0))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_set_parent()
    {
        $this
            ->given(
                $parent = new SUT('baz'),
                $node   = new SUT('foo', ['bar'], [], $parent)
            )
            ->when($result = $node->setParent(new SUT('qux')))
            ->then
                ->object($result)
                    ->isIdenticalTo($parent);
    }

    public function case_get_parent()
    {
        $this
            ->given(
                $parent = new SUT('qux'),
                $node   = new SUT('foo', ['bar'], [], new SUT('baz')),
                $node->setParent($parent)
            )
            ->when($result = $node->getParent())
            ->then
                ->object($result)
                    ->isIdenticalTo($parent);
    }

    public function case_get_data()
    {
        $this
            ->given($node = new SUT('foo'))
            ->when($result = $node->getData())
            ->then
                ->array($result)
                    ->isEmpty()

            ->when(
                $result[] = 'bar',
                $result[] = 'baz',
                $result   = $node->getData()
            )
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_get_data_by_reference()
    {
        $this
            ->given($node = new SUT('foo'))
            ->when($result = &$node->getData())
            ->then
                ->array($result)
                    ->isEmpty()

            ->when(
                $result[] = 'bar',
                $result[] = 'baz',
                $result   = $node->getData()
            )
            ->then
                ->array($result)
                    ->isEqualTo(['bar', 'baz']);
    }
}
