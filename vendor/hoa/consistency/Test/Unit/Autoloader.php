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

namespace Hoa\Consistency\Test\Unit;

use Hoa\Consistency\Autoloader as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Consistency\Test\Unit\Autoloader.
 *
 * Test suite of the autoloader.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Autoloader extends Test\Unit\Suite
{
    public function case_add_namespace_prepend()
    {
        $this
            ->given(
                $autoloader     = new SUT(),
                $prefix         = 'Foo\Bar\\',
                $baseDirectoryA = 'Source/Foo/Bar/',
                $baseDirectoryB = 'Source/Foo/Bar/'
            )
            ->when(
                $autoloader->addNamespace($prefix, $baseDirectoryA),
                $result = $autoloader->addNamespace($prefix, $baseDirectoryB)
            )
            ->then
                ->boolean($autoloader->hasBaseDirectory($prefix))
                    ->isTrue()
                ->array($autoloader->getBaseDirectories($prefix))
                    ->isEqualTo([
                        $baseDirectoryB,
                        $baseDirectoryA
                    ]);
    }

    public function case_add_namespace_append()
    {
        $this
            ->given(
                $autoloader     = new SUT(),
                $prefix         = 'Foo\Bar\\',
                $baseDirectoryA = 'Source/Foo/Bar/',
                $baseDirectoryB = 'Source/Foo/Bar/'
            )
            ->when(
                $autoloader->addNamespace($prefix, $baseDirectoryA),
                $result = $autoloader->addNamespace($prefix, $baseDirectoryB)
            )
            ->then
                ->boolean($autoloader->hasBaseDirectory($prefix))
                    ->isTrue()
                ->array($autoloader->getBaseDirectories($prefix))
                    ->isEqualTo([
                        $baseDirectoryA,
                        $baseDirectoryB
                    ]);
    }

    public function case_add_namespace_with_invalid_prefix()
    {
        $this
            ->given(
                $autoloader    = new SUT(),
                $prefix        = '\\\\Foo\Bar',
                $baseDirectory = 'Source/Foo/Bar/'
            )
            ->when($result = $autoloader->addNamespace($prefix, $baseDirectory))
            ->then
                ->boolean($autoloader->hasBaseDirectory('Foo\Bar\\'))
                    ->isTrue()
                ->array($autoloader->getBaseDirectories('Foo\Bar\\'))
                    ->isEqualTo([$baseDirectory]);
    }

    public function case_add_namespace_with_invalid_base_directory()
    {
        $this
            ->given(
                $autoloader    = new SUT(),
                $prefix        = 'Foo\Bar\\',
                $baseDirectory = 'Source/Foo/Bar'
            )
            ->when($result = $autoloader->addNamespace($prefix, $baseDirectory))
            ->then
                ->boolean($autoloader->hasBaseDirectory('Foo\Bar\\'))
                    ->isTrue()
                ->array($autoloader->getBaseDirectories('Foo\Bar\\'))
                    ->isEqualTo(['Source/Foo/Bar/']);
    }

    public function case_add_namespace_with_crazy_invalid_base_directory()
    {
        $this
            ->given(
                $autoloader    = new SUT(),
                $prefix        = 'Foo\Bar\\',
                $baseDirectory = 'Source/Foo/Bar/////'
            )
            ->when($result = $autoloader->addNamespace($prefix, $baseDirectory))
            ->then
                ->boolean($autoloader->hasBaseDirectory('Foo\Bar\\'))
                    ->isTrue()
                ->array($autoloader->getBaseDirectories('Foo\Bar\\'))
                    ->isEqualTo(['Source/Foo/Bar/']);
    }

    public function case_load()
    {
        $this
            ->given(
                $autoloader = new \Mock\Hoa\Consistency\Autoloader(),
                $autoloader->addNamespace('Foo\Bar\\', 'Source/Foo/Bar/'),
                $this->calling($autoloader)->requireFile = function ($file) {
                    return $file;
                }
            )
            ->when($result = $autoloader->load('Foo\Bar\Baz\Qux'))
            ->then
                ->string($result)
                    ->isEqualTo('Source/Foo/Bar/Baz/Qux.php');
    }

    public function case_load_invalid_entity()
    {
        $this
            ->given($autoloader = new SUT())
            ->when($result = $autoloader->load('Foo'))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_load_flex_entity()
    {
        $self = $this;

        $this
            ->given(
                $autoloader = new \Mock\Hoa\Consistency\Autoloader(),
                $autoloader->addNamespace('Foo\Bar\\', 'Source/Foo/'),
                $this->calling($autoloader)->runAutoloaderStack = function ($entity) use ($self, &$called) {
                    $called = true;
                    $self
                        ->string($entity)
                            ->isEqualTo('Foo\Bar\Baz\Baz');

                    return;
                },
                $autoloader->register()
            )
            ->when($result = $autoloader->load('Foo\Bar\Baz'))
            ->then
                ->variable($result)
                    ->isNull()
                ->boolean($called)
                    ->isTrue();
    }

    public function case_load_unmapped_flex_entity()
    {
        $self = $this;

        $this
            ->given(
                $autoloader = new \Mock\Hoa\Consistency\Autoloader(),
                $this->calling($autoloader)->runAutoloaderStack = function ($entity) use ($self, &$called) {
                    $called = true;

                    return;
                },
                $autoloader->register()
            )
            ->when($result = $autoloader->load('Foo\Bar\Baz'))
            ->then
                ->variable($result)
                    ->isNull()
                ->variable($called)
                    ->isNull();
    }

    public function case_require_existing_file()
    {
        $this
            ->given(
                $autoloader = new SUT(),

                $this->function->file_exists = true,

                $constantName = 'HOA_TEST_' . uniqid(),
                $filename     = 'hoa://Test/Vfs/Foo?type=file',

                file_put_contents($filename, '<?php define("' . $constantName . '", "BAR");')
            )
            ->when($result = $autoloader->requireFile($filename))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->string(constant($constantName))
                    ->isEqualTo('BAR');
    }

    public function case_require_not_existing_file()
    {
        $this
            ->given(
                $autoloader                  = new SUT(),
                $this->function->file_exists = false
            )
            ->when($result = $autoloader->requireFile('/hoa/flatland'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_has_not_base_directory()
    {
        $this
            ->given($autoloader = new SUT())
            ->when($result = $autoloader->hasBaseDirectory('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_get_base_undeclared_namespace_prefix()
    {
        $this
            ->given($autoloader = new SUT())
            ->when($result = $autoloader->getBaseDirectories('foo'))
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_dnew()
    {
        $this
            ->given($classname = 'Hoa\Consistency\Autoloader')
            ->when($result = SUT::dnew($classname))
            ->then
                ->object($result)
                    ->isInstanceOf($classname);
    }

    public function case_dnew_unknown_class()
    {
        $this
            ->given($this->function->spl_autoload_call = null)
            ->exception(function () {
                SUT::dnew('Foo');
            })
                ->isInstanceOf('ReflectionException');
    }

    public function case_get_loaded_classes()
    {
        $this
            ->given(
                $declaredClasses                      = get_declared_classes(),
                $this->function->get_declared_classes = $declaredClasses
            )
            ->when($result = SUT::getLoadedClasses())
            ->then
                ->array($result)
                    ->isEqualTo($declaredClasses);
    }

    public function case_register()
    {
        $self = $this;

        $this
            ->given($autoloader = new SUT())
            ->when($result = $autoloader->register())
            ->then
                ->boolean($result)
                    ->isTrue()
                ->array($autoloader->getRegisteredAutoloaders())
                    ->isEqualTo(spl_autoload_functions());
    }

    public function case_unregister()
    {
        $this
            ->given(
                $autoloader               = new SUT(),
                $oldRegisteredAutoloaders = $autoloader->getRegisteredAutoloaders()
            )
            ->when($result = $autoloader->register())
            ->then
                ->boolean($result)
                    ->isTrue()
                ->integer(count($autoloader->getRegisteredAutoloaders()))
                    ->isEqualTo(count($oldRegisteredAutoloaders) + 1)

            ->when($result = $autoloader->unregister())
            ->then
                ->boolean($result)
                    ->isTrue()
                ->array($autoloader->getRegisteredAutoloaders())
                    ->isEqualTo($oldRegisteredAutoloaders);
    }
}
