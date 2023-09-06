<?php

namespace Plank\Mediable\Tests\Mocks;

use Plank\Mediable\HandlesMediaUploadExceptions;

class SampleExceptionHandler
{
    use HandlesMediaUploadExceptions;

    /**
     * Render an exception into a HttpException for testing purposes.
     *
     * In laravel's exception handler we would call transformMediaUploadException()
     * inside the render() method, but instead of returning its result,
     * we would pass it to the parent::render().
     *
     * @param  \Exception $e
     * @return \Symfony\Component\HttpKernel\Exception\HttpException|\Exception
     */
    public function render(\Exception $e)
    {
        return $this->transformMediaUploadException($e);
    }
}
