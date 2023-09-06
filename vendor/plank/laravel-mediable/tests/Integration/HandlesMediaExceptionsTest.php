<?php

namespace Plank\Mediable\Tests\Integration;

use Exception;
use Plank\Mediable\Exceptions\MediaUpload\ConfigurationException;
use Plank\Mediable\Exceptions\MediaUpload\FileExistsException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException;
use Plank\Mediable\Exceptions\MediaUpload\FileSizeException;
use Plank\Mediable\Exceptions\MediaUpload\ForbiddenException;
use Plank\Mediable\Tests\Mocks\SampleExceptionHandler;
use Plank\Mediable\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandlesMediaExceptionsTest extends TestCase
{
    public function test_it_returns_a_403_for_disallowed_disk()
    {
        $e = (new SampleExceptionHandler())->render(
            ForbiddenException::diskNotAllowed('foo')
        );

        $this->assertHttpException($e, 403);
    }

    public function test_it_returns_a_404_for_missing_file()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotFoundException::fileNotFound('non/existing.jpg')
        );

        $this->assertHttpException($e, 404);
    }

    public function test_it_returns_a_409_on_duplicate_file()
    {
        $e = (new SampleExceptionHandler())->render(
            FileExistsException::fileExists('already/existing.jpg')
        );

        $this->assertHttpException($e, 409);
    }

    public function test_it_returns_a_413_for_too_big_file()
    {
        $e = (new SampleExceptionHandler())->render(
            FileSizeException::fileIsTooBig(3, 2)
        );

        $this->assertHttpException($e, 413);
    }

    public function test_it_returns_a_415_for_type_mismatch()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotSupportedException::strictTypeMismatch('text/foo', 'bar')
        );

        $this->assertHttpException($e, 415);
    }

    public function test_it_returns_a_415_for_unknown_type()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotSupportedException::unrecognizedFileType('text/foo', 'bar')
        );

        $this->assertHttpException($e, 415);
    }

    public function test_it_returns_a_415_for_restricted_type()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotSupportedException::mimeRestricted('text/foo', ['text/bar'])
        );

        $this->assertHttpException($e, 415);
    }

    public function test_it_returns_a_415_for_restricted_extension()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotSupportedException::extensionRestricted('foo', ['bar'])
        );

        $this->assertHttpException($e, 415);
    }

    public function test_it_returns_a_415_for_restricted_aggregate_type()
    {
        $e = (new SampleExceptionHandler())->render(
            FileNotSupportedException::aggregateTypeRestricted('foo', ['bar'])
        );

        $this->assertHttpException($e, 415);
    }

    public function test_it_returns_a_500_for_other_exception_types()
    {
        $e = (new SampleExceptionHandler())->render(
            new ConfigurationException()
        );

        $this->assertHttpException($e, 500);
    }

    public function test_it_skips_any_other_exception()
    {
        $e = (new SampleExceptionHandler())->render(
            new Exception()
        );

        $this->assertFalse($e instanceof HttpException);
    }

    protected function assertHttpException($e, $code)
    {
        $this->assertInstanceOf(HttpException::class, $e);
        /** @var HttpException $e */
        $this->assertEquals($code, $e->getStatusCode());
    }
}
