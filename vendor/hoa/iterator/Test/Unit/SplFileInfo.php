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

namespace Hoa\Iterator\Test\Unit;

use Hoa\Iterator as LUT;
use Hoa\Test;

/**
 * Class \Hoa\Iterator\Test\Unit\SplFileInfo.
 *
 * Test suite of the SplFileInfo class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class SplFileInfo extends Test\Unit\Suite
{
    public function case_file()
    {
        $this
            ->given($pathname = 'hoa://Test/Vfs/Foo.bar?type=file')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isFile())
                    ->isTrue()
                ->string($result->getType())
                    ->isEqualTo('file');
    }

    public function case_directory()
    {
        $this
            ->given($pathname = 'hoa://Test/Vfs/Foo?type=directory')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isDir())
                    ->isTrue()
                ->string($result->getType())
                    ->isEqualTo('dir');
    }

    public function case_path_informations()
    {
        $this
            ->given(
                $relativePath     = 'hoa://Test/Vfs/A/B/',
                $relativePathname = 'C/Foo.bar',
                $pathname         = $relativePath . $relativePathname
            )
            ->when($result = new LUT\SplFileInfo($pathname . '?type=file', $relativePath))
            ->then
                ->boolean($result->isFile())
                    ->isTrue()
                ->string($result->getBasename())
                    ->isEqualTo('Foo.bar?type=file')
                ->string($result->getExtension())
                    ->isEqualTo('bar?type=file')
                ->string($result->getRelativePath())
                    ->isEqualTo($relativePath)
                ->string($result->getRelativePathname())
                    ->isEqualTo($relativePathname . '?type=file')
                ->string($result->getPath())
                    ->isEqualTo('hoa://Test/Vfs/A/B/C')
                ->string($result->getPathname())
                    ->isEqualTo($pathname . '?type=file');
    }

    public function case_times()
    {
        $this
            ->given(
                $timestamp = $this->realdom->boundinteger(
                    $this->realdom->timestamp('yesterday'),
                    $this->realdom->timestamp('tomorrow')
                ),
                $atime    = $this->sample($timestamp),
                $ctime    = $this->sample($timestamp),
                $mtime    = $this->sample($timestamp),
                $pathname =
                    'hoa://Test/Vfs/Foo.bar?' .
                    http_build_query([
                        'type'  => 'file',
                        'atime' => $atime,
                        'ctime' => $ctime,
                        'mtime' => $mtime
                    ])
            )
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->integer($result->getATime())
                    ->isEqualTo($atime)
                ->integer($result->getCTime())
                    ->isEqualTo($ctime)
                ->integer($result->getMTime())
                    ->isEqualTo($mtime);
    }

    public function case_permissions()
    {
        $this
            ->given($pathname = 'hoa://Test/Vfs/Fo.bar?type=file&permissions=0744')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isReadable())
                    ->isTrue()
                ->boolean($result->isWritable())
                    ->isTrue()
                ->boolean($result->isExecutable())
                    ->isTrue()

            ->given($pathname = 'hoa://Test/Vfs/Foo.bar?type=file&permissions=0644')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isReadable())
                    ->isTrue()
                ->boolean($result->isWritable())
                    ->isTrue()
                ->boolean($result->isExecutable())
                    ->isFalse()

            ->given($pathname = 'hoa://Test/Vfs/Fooo.bar?type=file&permissions=0444')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isReadable())
                    ->isTrue()
                ->boolean($result->isWritable())
                    ->isFalse()
                ->boolean($result->isExecutable())
                    ->isFalse()

            ->given($pathname = 'hoa://Test/Vfs/Foooo.bar?type=file&permissions=0044')
            ->when($result = new LUT\SplFileInfo($pathname))
            ->then
                ->boolean($result->isReadable())
                    ->isFalse()
                ->boolean($result->isWritable())
                    ->isFalse()
                ->boolean($result->isExecutable())
                    ->isFalse();
    }
}
