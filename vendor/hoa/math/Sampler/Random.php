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

namespace Hoa\Math\Sampler;

/**
 * Class \Hoa\Math\Sampler\Random.
 *
 * Random sampler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Random extends Sampler
{
    /**
     * Construct.
     *
     * @return  void
     */
    public function construct()
    {
        if (null === $this->_parameters->getParameter('integer.min')) {
            $this->_parameters->setParameter(
                'integer.min',
                max(
                    -mt_getrandmax(),
                    $this->_parameters->getParameter('integer.min')
                )
            );
        }

        if (null === $this->_parameters->getParameter('integer.max')) {
            $this->_parameters->setParameter(
                'integer.max',
                min(
                    mt_getrandmax(),
                    $this->_parameters->getParameter('integer.max')
                )
            );
        }

        return;
    }

    /**
     * Generate a discrete uniform distribution.
     *
     * @param   int  $lower    Lower bound value.
     * @param   int  $upper    Upper bound value.
     * @return  int
     */
    protected function _getInteger($lower, $upper)
    {
        return mt_rand($lower, $upper);
    }

    /**
     * Generate a continuous uniform distribution.
     *
     * @param   float      $lower    Lower bound value.
     * @param   float      $upper    Upper bound value.
     * @return  float
     */
    protected function _getFloat($lower, $upper)
    {
        return $lower + lcg_value() * abs($upper - $lower);
    }
}
