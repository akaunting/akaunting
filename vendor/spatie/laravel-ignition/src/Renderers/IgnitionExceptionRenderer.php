<?php

namespace Spatie\LaravelIgnition\Renderers;

use Illuminate\Contracts\Foundation\ExceptionRenderer;

class IgnitionExceptionRenderer implements ExceptionRenderer
{
    protected ErrorPageRenderer $errorPageHandler;

    public function __construct(ErrorPageRenderer $errorPageHandler)
    {
        $this->errorPageHandler = $errorPageHandler;
    }

    public function render($throwable)
    {
        ob_start();

        $this->errorPageHandler->render($throwable);

        return ob_get_clean();
    }
}
