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

namespace Hoa\Iterator\Recursive;

use Hoa\Iterator;

/**
 * Class \Hoa\Iterator\Recursive\RegularExpression.
 *
 * Re-implement the SPL RecursiveRegexIterator class.
 * There are too many bugs in php-src and HHVM, so we re-implement it from
 * scratch without extending the existing class.
 *
 * Inspired by hhvm://hphp/system/php/spl/iterators/RecursiveRegexIterator.php
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class RegularExpression extends Iterator\RegularExpression implements Recursive
{
    /**
     * Constructor.
     *
     * @param   \RecursiveIterator  $iterator     The recursive iterator to
     *                                            apply this regex filter to.
     * @param   string              $regex        The regular expression to
     *                                            match.
     * @param   int                 $mode         Operation mode, please see the
     *                                            \RegexIterator::setMode method.
     * @param   int                 $flags        Special flags, please see the
     *                                            \RegexIterator::setFlags method.
     * @param   int                 $pregFlags    Regular expression flags,
     *                                            please see
     *                                            \RegexIterator constants.
     */
    public function __construct(
        \RecursiveIterator $iterator,
        $regex,
        $mode      = self::MATCH,
        $flags     = 0,
        $pregFlags = 0
    ) {
        parent::__construct($iterator, $regex, $mode, $flags, $pregFlags);

        return;
    }

    /**
     * Get accept status.
     *
     * @return  bool
     */
    public function accept()
    {
        return
            true === $this->hasChildren() ||
            true === parent::accept();
    }

    /**
     * Get an iterator for the current entry.
     *
     * @return  \Hoa\Iterator\Recursive\RegularExpression
     */
    public function getChildren()
    {
        return new static(
            true === $this->hasChildren()
                ? $this->getInnerIterator()->getChildren()
                : null,
            $this->getRegex(),
            $this->getMode(),
            $this->getFlags(),
            $this->getPregFlags()
        );
    }

    /**
     * Check whether an iterator can be obtained for the current entry.
     *
     * @return  bool
     */
    public function hasChildren()
    {
        return $this->getInnerIterator()->hasChildren();
    }
}
