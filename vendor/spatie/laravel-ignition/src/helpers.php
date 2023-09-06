<?php

use Spatie\LaravelIgnition\Renderers\ErrorPageRenderer;

if (! function_exists('ddd')) {
    function ddd()
    {
        $args = func_get_args();

        if (count($args) === 0) {
            throw new Exception('You should pass at least 1 argument to `ddd`');
        }

        call_user_func_array('dump', $args);

        $renderer = app()->make(ErrorPageRenderer::class);

        $exception = new Exception('Dump, Die, Debug');

        $renderer->render($exception);

        die();
    }
}
