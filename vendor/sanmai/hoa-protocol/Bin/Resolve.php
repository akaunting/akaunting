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

namespace Hoa\Protocol\Bin;

use Hoa\Console;
use Hoa\Protocol;

/**
 * This command resolves some `hoa://` paths.
 */
class Resolve extends Console\Dispatcher\Kit
{
    /**
     * Options description.
     */
    protected $options = [
        ['exists',     Console\GetOption::NO_ARGUMENT, 'E'],
        ['unfold',     Console\GetOption::NO_ARGUMENT, 'u'],
        ['tree',       Console\GetOption::NO_ARGUMENT, 't'],
        ['no-verbose', Console\GetOption::NO_ARGUMENT, 'V'],
        ['help',       Console\GetOption::NO_ARGUMENT, 'h'],
        ['help',       Console\GetOption::NO_ARGUMENT, '?']
    ];



    /**
     * The entry method.
     */
    public function main()
    {
        $exists  = true;
        $unfold  = false;
        $tree    = false;
        $verbose = Console::isDirect(STDOUT);

        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case 'E':
                    $exists = false;

                    break;

                case 'u':
                    $unfold = true;

                    break;

                case 't':
                    $tree = true;

                    break;

                case 'V':
                    $verbose = false;

                    break;

                case 'h':
                case '?':
                    return $this->usage();

                case '__ambiguous':
                    $this->resolveOptionAmbiguity($v);

                    break;
            }
        }

        $this->parser->listInputs($path);

        if (null === $path) {
            return $this->usage();
        }

        if (true === $tree) {
            $protocol = Protocol::getInstance();
            $foo      = substr($path, 0, 6);

            if ('hoa://' !== $foo) {
                return 1;
            }

            $path    = substr($path, 6);
            $current = $protocol;

            foreach (explode('/', $path) as $component) {
                if (!isset($current[$component])) {
                    break;
                }

                $current = $current[$component];
            }

            echo $current;

            return 0;
        }

        if (true === $verbose) {
            echo
                Console\Cursor::colorize('foreground(yellow)'),
                $path,
                Console\Cursor::colorize('normal'),
                ' is equivalent to:', "\n";
        }

        $resolved = Protocol\Protocol::getInstance()->resolve($path, $exists, $unfold);

        foreach ((array) $resolved as $r) {
            echo $r, "\n";
        }

        return 0;
    }

    /**
     * The command usage.
     */
    public function usage()
    {
        echo
            'Usage   : protocol:resolve <options> path', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'E'    => 'Do not check if the resolution result exists.',
                'u'    => 'Unfold all possible results.',
                't'    => 'Print the tree from the path.',
                'V'    => 'No-verbose, i.e. be as quiet as possible, just print ' .
                          'essential information.',
                'help' => 'This help.'
            ]), "\n";
    }
}

__halt_compiler();
Resolve `hoa://` paths.
