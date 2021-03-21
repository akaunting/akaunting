<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal\Resource;

interface ResourceInterface
{
    /**
     * Get the resource key.
     *
     * @return string
     */
    public function getResourceKey();

    /**
     * Get the data.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Get the transformer.
     *
     * @return callable|\League\Fractal\TransformerAbstract
     */
    public function getTransformer();

    /**
     * Set the data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data);

    /**
     * Set the transformer.
     *
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     *
     * @return $this
     */
    public function setTransformer($transformer);
}
