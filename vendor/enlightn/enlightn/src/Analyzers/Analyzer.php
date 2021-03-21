<?php

namespace Enlightn\Enlightn\Analyzers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Throwable;

abstract class Analyzer
{
    const SEVERITY_CRITICAL = 'critical';
    const SEVERITY_MAJOR = 'major';
    const SEVERITY_MINOR = 'minor';
    const SEVERITY_INFO = 'info';

    /**
     * The base URL of the Enlightn documentation.
     *
     * @var string
     */
    const DOCS_URL = 'https://www.laravel-enlightn.com/docs';

    /**
     * The category of the analyzer.
     *
     * @var string|null
     */
    public $category = null;

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = null;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = null;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = null;

    /**
     * The error message describing the analyzer insights.
     *
     * @var string|null
     */
    public $errorMessage = null;

    /**
     * The application paths and associated line numbers to flag.
     *
     * @var array
     */
    public $traces = [];

    /**
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = true;

    /**
     * The exception thrown during the analysis.
     *
     * @var array
     */
    protected $exceptionMessage = null;

    /**
     * The stack trace of the exception thrown during the analysis.
     *
     * @var array
     */
    protected $stackTrace = null;

    /**
     * Determine whether the analyzer passed.
     *
     * @var bool
     */
    protected $passed = true;

    /**
     * Determine whether the analyzer was skipped.
     *
     * @var bool
     */
    protected $skipped = false;

    /**
     * Run the analyzer.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function run(Application $app)
    {
        if (method_exists($this, 'skip') && $this->skip()) {
            $this->markSkipped();

            return;
        }

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        $app->call([$this, $method]);
    }

    /**
     * Get the error message pertaining to the analysis.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return method_exists($this, 'errorMessage') ? $this->errorMessage() : $this->errorMessage;
    }

    /**
     * Add an associated path and line number trace.
     *
     * @param  string  $path
     * @param  int  $lineNumber
     * @param  string|null  $details
     * @return $this
     */
    public function addTrace(string $path, $lineNumber = 0, $details = null)
    {
        if ($lineNumber == 0) {
            return $this->markFailed();
        }

        if ($this->isIgnoredError($path, $details)) {
            return $this;
        }

        if (! in_array($trace = new Trace($path, $lineNumber, $details), $this->traces)) {
            $this->traces[] = $trace;
        }

        return $this->markFailed();
    }

    /**
     * Push a trace to the traces array.
     *
     * @param \Enlightn\Enlightn\Analyzers\Trace $trace
     * @return $this
     */
    public function pushTrace(Trace $trace)
    {
        if ($this->isIgnoredError($trace->path, $trace->details)) {
            return $this;
        }

        if (! in_array($trace, $this->traces)) {
            $this->traces[] = $trace;
        }

        return $this->markFailed();
    }

    /**
     * Record an exception that was thrown during the analysis.
     *
     * @param \Throwable $e
     * @return $this
     */
    public function recordException(Throwable $e)
    {
        $this->exceptionMessage = $e->getMessage();

        $this->stackTrace = $e->getTraceAsString();

        return $this->markSkipped();
    }

    /**
     * Set an exception message for the analyzer.
     *
     * @param string $message
     * @return $this
     */
    public function setExceptionMessage(string $message)
    {
        $this->exceptionMessage = $message;

        return $this->markSkipped();
    }

    /**
     * Mark the analyzer as failed.
     *
     * @return $this
     */
    public function markFailed()
    {
        $this->passed = false;

        return $this;
    }

    /**
     * Mark the analyzer as skipped.
     *
     * @return $this
     */
    public function markSkipped()
    {
        $this->skipped = true;

        return $this;
    }

    /**
     * Get the analyzer information.
     *
     * @return array
     */
    public function getInfo()
    {
        return [
            'title' => $this->title,
            'category' => $this->category,
            'severity' => $this->severity,
            'timeToFix' => $this->timeToFix,
            'status' => $this->getStatus(),
            'exception' => $this->exceptionMessage,
            'error' => ($this->getStatus() == 'failed') ? $this->getErrorMessage() : null,
            'traces' => $this->traces,
            'docsUrl' => $this->getDocsUrl(),
            'reportable' => ! in_array(static::class, config('enlightn.dont_report', [])),
            'class' => static::class,
            'stackTrace' => $this->stackTrace,
        ];
    }

    /**
     * Get the analyzer status.
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->runFailed()) {
            return 'error';
        } elseif ($this->skipped()) {
            return 'skipped';
        } else {
            return $this->passed() ? 'passed' : 'failed';
        }
    }

    /**
     * Determine whether the analyzer passed.
     *
     * @return bool
     */
    public function passed()
    {
        return $this->passed;
    }

    /**
     * Determine whether the analyzer was skipped.
     *
     * @return bool
     */
    public function skipped()
    {
        return $this->skipped;
    }

    /**
     * Determine whether the analyzer run failed with an exception.
     *
     * @return bool
     */
    public function runFailed()
    {
        return ! is_null($this->exceptionMessage);
    }

    /**
     * Get the documentation URL for this analyzer.
     *
     * @return bool
     */
    public function getDocsUrl()
    {
        $page = $this->docsPageName ??
                Str::kebab(
                    str_replace(
                        ['CSRF', 'SQL', 'HSTS', 'NPlusOne', 'XSS', 'PHP'],
                        ['Csrf', 'Sql', 'Hsts', 'Nplusone', 'Xss', 'Php'],
                        class_basename(get_class($this))
                    )
                );

        return self::DOCS_URL.'/'.strtolower($this->category).'/'.$page.'.html';
    }

    /**
     * Determine whether the analyzer should skip if the environment is local.
     *
     * @return bool
     */
    public function isLocalAndShouldSkip()
    {
        return config('app.env') === 'local' && config('enlightn.skip_env_specific', false);
    }

    /**
     * Determine whether the error should be ignored.
     *
     * @param string $path
     * @param string|null $details
     * @return bool
     */
    public function isIgnoredError(string $path, $details)
    {
        $ignoredErrors = config('enlightn.ignore_errors', []);

        if (! isset($ignoredErrors[static::class])) {
            return false;
        }

        return collect($ignoredErrors[static::class])
            ->contains(function ($info) use ($path, $details) {
                return ($info['path'] == $path || base_path(trim($info['path'], '/')) == $path) &&
                    Str::is($info['details'], $details);
            });
    }
}
