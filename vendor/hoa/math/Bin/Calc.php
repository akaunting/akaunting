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

namespace Hoa\Math\Bin;

use Hoa\Compiler;
use Hoa\Console;
use Hoa\File;
use Hoa\Math;

/**
 * Class \Hoa\Math\Bin\Calc.
 *
 * A simple calculator.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Calc extends Console\Dispatcher\Kit
{
    /**
     * Options description.
     *
     * @var array
     */
    protected $options = [
        ['help', Console\GetOption::NO_ARGUMENT, 'h'],
        ['help', Console\GetOption::NO_ARGUMENT, '?']
    ];



    /**
     * The entry method.
     *
     * @return  int
     */
    public function main()
    {
        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case 'h':
                case '?':
                    return $this->usage();

                case '__ambiguous':
                    $this->resolveOptionAmbiguity($v);

                    break;
            }
        }

        $this->parser->listInputs($expression);

        $compiler = Compiler\Llk::load(
            new File\Read('hoa://Library/Math/Arithmetic.pp')
        );
        $visitor  = new Math\Visitor\Arithmetic();
        $dump     = new Compiler\Visitor\Dump();

        if (null !== $expression) {
            $ast = $compiler->parse($expression);
            echo $expression . ' = ' . $visitor->visit($ast), "\n";

            return;
        }

        $readline   = new Console\Readline();
        $readline->setAutocompleter(
            new Console\Readline\Autocompleter\Word(
                array_merge(
                    array_keys($visitor->getConstants()->getArrayCopy()),
                    array_keys($visitor->getFunctions()->getArrayCopy())
                )
            )
        );
        $handle     = null;
        $expression = 'h';

        do {
            switch ($expression) {
                case 'h':
                case 'help':
                    echo
                        'Usage:', "\n",
                        '    h[elp]       to print this help;', "\n",
                        '    c[onstants]  to print available constants;', "\n",
                        '    f[unctions]  to print available functions;', "\n",
                        '    e[xpression] to print the current expression;', "\n",
                        '    d[ump]       to dump the tree of the expression;', "\n",
                        '    q[uit]       to quit.', "\n";

                    break;

                case 'c':
                case 'constants':
                    echo
                        implode(
                            ', ',
                            array_keys(
                                $visitor->getConstants()->getArrayCopy()
                            )
                        ),
                        "\n";

                    break;

                case 'f':
                case 'functions':
                    echo
                        implode(
                            ', ',
                            array_keys(
                                $visitor->getFunctions()->getArrayCopy()
                            )
                        ),
                        "\n";

                    break;

                case 'e':
                case 'expression':
                    echo $handle, "\n";

                    break;

                case 'd':
                case 'dump':
                    if (null === $handle) {
                        echo 'Type a valid expression before (“> 39 + 3”).', "\n";
                    } else {
                        echo $dump->visit($compiler->parse($handle)), "\n";
                    }

                    break;

                case 'q':
                case 'quit':
                    break 2;

                default:
                    if (null === $expression) {
                        break;
                    }

                    try {
                        echo $visitor->visit($compiler->parse($expression)), "\n";
                    } catch (Compiler\Exception $e) {
                        echo $e->getMessage(), "\n";

                        break;
                    }

                    $handle = $expression;

                    break;
            }
        } while (false !== $expression = $readline->readLine('> '));

        return;
    }

    /**
     * The command usage.
     *
     * @return  int
     */
    public function usage()
    {
        echo
            'Usage   : math:calc <options> [expression]', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'help' => 'This help.'
            ]), "\n";

        return;
    }
}

__halt_compiler();
A simple calculator.
