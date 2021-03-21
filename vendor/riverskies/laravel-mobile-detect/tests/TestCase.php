<?php

use Detection\MobileDetect;
use Philo\Blade\Blade;
use Riverskies\Laravel\MobileDetect\Contracts\BladeDirectiveInterface;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Blade template engine instance.
     * @var Blade
     */
    protected $blade;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $this->app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $this->app;
    }

    /**
     * Set up mobile detect mock expectations.
     * 
     * @param $callback
     */
    protected function expectMobileDetectReturn($callback)
    {
        $mobileDetect = $this->prophesize(MobileDetect::class);

        $callback($mobileDetect);

        $this->app->singleton('mobile-detect', function($app) use ($mobileDetect) {
            return $mobileDetect->reveal();
        });
    }

    /**
     * Sets up template engine to mimic Laravel.
     *
     * @param BladeDirectiveInterface $directive
     */
    protected function setUpTemplateEngine(BladeDirectiveInterface $directive)
    {
        list($views, $cache) = $this->createTestWorld($directive);

        $this->blade = new Blade($views, $cache);

        $this->blade->getCompiler()->directive(
            $directive->openingTag(), [$directive, 'openingHandler']
        );

        $this->blade->getCompiler()->directive(
            $directive->closingTag(), [$directive, 'closingHandler']
        );

        $this->blade->getCompiler()->directive(
            $directive->alternatingTag(), [$directive, 'alternatingHandler']
        );
    }

    /**
     * Creates the context.
     *
     * @param BladeDirectiveInterface $directive
     * @return array
     */
    protected function createTestWorld(BladeDirectiveInterface $directive)
    {
        list($resource, $view, $cache) = $this->getDirectories();

        @mkdir($resource);
        @mkdir($cache);
        @mkdir($view);

        @file_put_contents($view . '/test.blade.php', "
            @{$directive->openingTag()}
                <h1>Test</h1>
            @{$directive->closingTag()}
        ");

        @file_put_contents($view . '/test-else.blade.php', "
            @{$directive->openingTag()}
                <h1>Test</h1>
            @{$directive->alternatingTag()}
                <h1>Else</h1>
            @{$directive->closingTag()}
        ");

        return [$view, $cache];
    }

    /**
     * Helper to set the directories.
     *
     * @return array
     */
    protected function getDirectories()
    {
        $resource = __DIR__ . '/resources';
        $view =     __DIR__ . '/resources/views';
        $cache =    __DIR__ . '/resources/cache';

        return array($resource, $view, $cache);
    }

    /**
     * Delete a directory with recursive check.
     *
     * @param $dir
     * @return bool
     */
    protected function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    /**
     * Minifying HTML content.
     *
     * @link http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter#answer-5324014
     *
     * @param $data
     * @return mixed
     */
    protected function clean($data)
    {
        $regexp = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespaces other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        return preg_replace($regexp, "", $data);
    }

    /**
     * Tear down function.
     */
    public function tearDown()
    {
        list($resource, $view, $cache) = $this->getDirectories();

        $this->deleteDirectory($view);
        $this->deleteDirectory($cache);
        $this->deleteDirectory($resource);
    }
}
