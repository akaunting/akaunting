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
 * Class \Hoa\Iterator\Test\Unit\Directory.
 *
 * Test suite of the directory iterator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Directory extends Test\Unit\Suite
{
    public function case_classic()
    {
        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A?type=file'),
                resolve('hoa://Test/Vfs/Root/Aa?type=file'),
                resolve('hoa://Test/Vfs/Root/Aaa?type=file'),
                $iterator = new LUT\Directory($root),
                $result   = []
            )
            ->when(function () use ($iterator, &$result) {
                foreach ($iterator as $key => $file) {
                    $result[$key] = $file->getFilename();

                    $this
                        ->object($file)
                            ->isInstanceOf(LUT\Directory::class);
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'A',
                        1 => 'Aa',
                        2 => 'Aaa'
                    ]);
    }

    public function case_seek_and_dots()
    {
        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/.?type=directory'),
                resolve('hoa://Test/Vfs/Root/..?type=directory'),
                resolve('hoa://Test/Vfs/Root/Skip?type=file'),
                resolve('hoa://Test/Vfs/Root/Gotcha?type=file'),
                $iterator = new LUT\Directory($root)
            )
            ->when($result = $iterator->current())
            ->then
                ->boolean($result->isDot())
                    ->isTrue()

            ->when(
                $iterator->next(),
                $result = $iterator->current()
            )
            ->then
                ->boolean($result->isDot())
                    ->isTrue()

            ->when(
                $iterator->seek(3),
                $result = $iterator->current()
            )
            ->then
                ->string($result->getFilename())
                    ->isEqualTo('Gotcha')

            ->when(
                $iterator->seek(2),
                $result = $iterator->current()
            )
            ->then
                ->string($result->getFilename())
                    ->isEqualTo('Skip');
    }

    public function case_recursive()
    {
        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A?type=file'),
                resolve('hoa://Test/Vfs/Root/Aa?type=file'),
                resolve('hoa://Test/Vfs/Root/Aaa?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo?type=directory'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar?type=directory'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/B?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/Bb?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/Bbb?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo/C?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo/Cc?type=file'),
                resolve('hoa://Test/Vfs/Root/Foo/Ccc?type=file'),
                $directory = new LUT\Recursive\Directory($root),
                $iterator  = new LUT\Recursive\Iterator($directory),
                $result    = []
            )
            ->when(function () use ($iterator, &$result) {
                foreach ($iterator as $file) {
                    $result[] = $file->getFilename();
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'A',
                        'Aa',
                        'Aaa',
                        'B',
                        'Bb',
                        'Bbb',
                        'C',
                        'Cc',
                        'Ccc'
                    ]);
    }

    public function case_splFileClassInfo()
    {
        $this
            ->given(
                $splFileInfo = 'Hoa\Iterator\SplFileInfo',
                $root        = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/a?type=file'),
                resolve('hoa://Test/Vfs/Root/b?type=file'),
                resolve('hoa://Test/Vfs/Root/c?type=file'),
                resolve('hoa://Test/Vfs/Root/d?type=file'),
                resolve('hoa://Test/Vfs/Root/e?type=file'),
                resolve('hoa://Test/Vfs/Root/f?type=file'),
                $iterator = new LUT\Directory(
                    $root,
                    $splFileInfo
                ),
                $result   = []
            )
            ->when(function () use ($iterator, $splFileInfo, &$result) {
                foreach ($iterator as $file) {
                    $this
                        ->object($file)
                            ->isInstanceOf($splFileInfo);

                    $result[] = $file->getFilename();
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'a',
                        'b',
                        'c',
                        'd',
                        'e',
                        'f'
                    ]);
    }

    public function case_recursive_splFileClassInfo()
    {
        $this
            ->given(
                $splFileInfo = 'Hoa\Iterator\SplFileInfo',
                $root        = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A?type=directory'),
                resolve('hoa://Test/Vfs/Root/A/a?type=file'),
                resolve('hoa://Test/Vfs/Root/A/b?type=file'),
                resolve('hoa://Test/Vfs/Root/A/c?type=file'),
                resolve('hoa://Test/Vfs/Root/B?type=directory'),
                resolve('hoa://Test/Vfs/Root/B/d?type=file'),
                resolve('hoa://Test/Vfs/Root/B/e?type=file'),
                resolve('hoa://Test/Vfs/Root/B/c?type=directory'),
                resolve('hoa://Test/Vfs/Root/B/c/f?type=file'),
                $directory = new LUT\Recursive\Directory(
                    $root,
                    LUT\FileSystem::CURRENT_AS_FILEINFO,
                    $splFileInfo
                ),
                $iterator  = new LUT\Recursive\Iterator($directory),
                $result    = []
            )
            ->when(function () use ($iterator, $splFileInfo, &$result) {
                foreach ($iterator as $file) {
                    $this
                        ->object($file)
                            ->isInstanceOf($splFileInfo);

                    $result[] = $file->getFilename();
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'a',
                        'b',
                        'c',
                        'd',
                        'e',
                        'f'
                    ]);
    }
}
