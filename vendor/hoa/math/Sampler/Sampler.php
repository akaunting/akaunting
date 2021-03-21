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

use Hoa\Consistency;
use Hoa\Math;
use Hoa\Zformat;

/**
 * Class \Hoa\Math\Sampler.
 *
 * Generic sampler.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Sampler implements Zformat\Parameterizable
{
    /**
     * Parameters.
     *
     * @var \Hoa\Zformat\Parameter
     */
    protected $_parameters = null;



    /**
     * Construct an abstract sampler.
     *
     * @param   array  $parameters    Parameters.
     */
    public function __construct(array $parameters = [])
    {
        $this->_parameters = new Zformat\Parameter(
            __CLASS__,
            [],
            [
                'integer.min' => -16,
                'integer.max' => 15,
                'float.min'   => -128.0,
                'float.max'   => 127.0
            ]
        );
        $this->_parameters->setParameters($parameters);

        if (null === $this->_parameters->getParameter('integer.min')) {
            $this->_parameters->setParameter('integer.min', PHP_INT_MIN);
        }

        if (null === $this->_parameters->getParameter('integer.max')) {
            $this->_parameters->setParameter('integer.max', PHP_INT_MAX);
        }

        if (null === $this->_parameters->getParameter('float.min')) {
            $this->_parameters->setParameter('float.min', PHP_INT_MIN);
        }

        if (null === $this->_parameters->getParameter('float.max')) {
            $this->_parameters->setParameter('float.max', PHP_INT_MAX);
        }

        $this->construct();

        return;
    }

    /**
     * Construct.
     *
     * @return  void
     */
    public function construct()
    {
        return;
    }

    /**
     * Get parameters.
     *
     * @return  \Hoa\Zformat\Parameter
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Generate a discrete uniform distribution.
     *
     * @param   int    $lower       Lower bound value.
     * @param   int    $upper       Upper bound value.
     * @param   array  &$exclude    Excluded values.
     * @return  int
     */
    public function getInteger(
        $lower          = null,
        $upper          = null,
        array &$exclude = null
    ) {
        if (null === $lower) {
            $lower = $this->_parameters->getParameter('integer.min');
        }

        if (null === $upper) {
            $upper = $this->_parameters->getParameter('integer.max');
        }

        if (null === $exclude) {
            if ($lower > $upper) {
                throw new Math\Exception(
                    'Unexpected values, integer %d should be lower than %d',
                    0,
                    [$lower, $upper]
                );
            }

            return $this->_getInteger($lower, $upper);
        }


        sort($exclude);

        $upper -= count($exclude);

        if ($lower > $upper) {
            throw new Math\Exception(
                'Unexpected values, integer %d should be lower than %d',
                1,
                [$lower, $upper]
            );
        }

        $sampled = $this->_getInteger($lower, $upper);

        foreach ($exclude as $e) {
            if ($sampled >= $e) {
                ++$sampled;
            } else {
                break;
            }
        }

        return $sampled;
    }

    /**
     * Generate a discrete uniform distribution.
     *
     * @param   int  $lower    Lower bound value.
     * @param   int  $upper    Upper bound value.
     * @return  int
     */
    abstract protected function _getInteger($lower, $upper);

    /**
     * Generate a continuous uniform distribution.
     *
     * @param   float   $lower    Lower bound value.
     * @param   float   $upper    Upper bound value.
     * @return  float
     */
    public function getFloat($lower = null, $upper = null)
    {
        if (null === $lower) {
            $lower = $this->_parameters->getParameter('float.min');
        }
            /*
            $lower = true === S_32\BITS
                         ? -3.4028235e38 + 1
                         : -1.7976931348623157e308 + 1;
            */

        if (null === $upper) {
            $upper = $this->_parameters->getParameter('float.max');
        }
            /*
            $upper = true === S_32\BITS
                         ? 3.4028235e38 - 1
                         : 1.7976931348623157e308 - 1;
            */

        if ($lower > $upper) {
            throw new Math\Exception(
                'Unexpected values, float %f should be lower than %f',
                2, [$lower, $upper]);
        }

        return $this->_getFloat($lower, $upper);
    }

    /**
     * Generate a continuous uniform distribution.
     *
     * @param   float      $lower    Lower bound value.
     * @param   float      $upper    Upper bound value.
     * @return  float
     */
    abstract protected function _getFloat($lower, $upper);

    /**
     * Get an exclude set.
     *
     * @return  array
     */
    public function getExcludeSet()
    {
        return [];
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Math\Sampler\Sampler');
