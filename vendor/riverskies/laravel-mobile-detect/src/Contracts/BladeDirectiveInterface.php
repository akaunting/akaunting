<?php

namespace Riverskies\Laravel\MobileDetect\Contracts;

interface BladeDirectiveInterface
{
    /**
     * Returns the Blade opening tag.
     *
     * @return string
     */
    public function openingTag();

    /**
     * Compiles the Blade opening.
     *
     * @param $expression
     * @return mixed
     */
    public function openingHandler($expression);

    /**
     * Returns the Blade closing tag.
     *
     * @return mixed
     */
    public function closingTag();

    /**
     * Compiles the Blade closing.
     *
     * @param $expression
     * @return mixed
     */
    public function closingHandler($expression);

    /**
     * Returns the Blade alternating tag.
     *
     * @return mixed
     */
    public function alternatingTag();

    /**
     * Compiles the Blade alternating tag.
     *
     * @param $expression
     * @return mixed
     */
    public function alternatingHandler($expression);
}
