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

namespace Hoa\Iterator;

/**
 * Class \Hoa\Iterator\Multiple.
 *
 * Extending the SPL MultipleIterator class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Multiple extends \MultipleIterator
{
    /**
     * Default value for each $infos.
     *
     * @var array
     */
    protected $_infos = [];



    /**
     * Attach iterator informations.
     * Add the $default argument that will be use when the iterator has reached
     * its end.
     *
     * @param   \Iterator  $iterator    Iterator.
     * @param   string     $infos       Informations to attach.
     * @param   mixed      $default     Default value.
     * @return  void
     */
    public function attachIterator(
        \Iterator $iterator,
        $infos   = null,
        $default = null
    ) {
        $out = parent::attachIterator($iterator, $infos);

        if (null === $infos) {
            $this->_infos[]       = $default;
        } else {
            $this->_infos[$infos] = $default;
        }

        return $out;
    }

    /**
     * Get the registered iterator instances.
     *
     * @return  array
     */
    public function current()
    {
        $out = parent::current();

        foreach ($out as $key => &$value) {
            if (null === $value) {
                $value = $this->_infos[$key];
            }
        }

        return $out;
    }
}
