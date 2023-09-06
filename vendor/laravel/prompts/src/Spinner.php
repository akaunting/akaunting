<?php

namespace Laravel\Prompts;

use Closure;
use RuntimeException;

class Spinner extends Prompt
{
    /**
     * How long to wait between rendering each frame.
     */
    public int $interval = 100;

    /**
     * The number of times the spinner has been rendered.
     */
    public int $count = 0;

    /**
     * Whether the spinner can only be rendered once.
     */
    public bool $static = false;

    /**
     * Create a new Spinner instance.
     */
    public function __construct(public string $message = '')
    {
        //
    }

    /**
     * Render the spinner and execute the callback.
     *
     * @template TReturn of mixed
     *
     * @param  \Closure(): TReturn  $callback
     * @return TReturn
     */
    public function spin(Closure $callback): mixed
    {
        $this->capturePreviousNewLines();

        if (! function_exists('pcntl_fork')) {
            $this->renderStatically($callback);
        }

        $this->hideCursor();

        $originalAsync = pcntl_async_signals(true);

        pcntl_signal(SIGINT, function () {
            $this->showCursor();
            exit();
        });

        try {
            $this->render();

            $pid = pcntl_fork();

            if ($pid === 0) {
                while (true) { // @phpstan-ignore-line
                    $this->render();

                    $this->count++;

                    usleep($this->interval * 1000);
                }
            } else {
                $result = $callback();
                posix_kill($pid, SIGHUP);
                $lines = explode(PHP_EOL, $this->prevFrame);
                $this->moveCursor(-999, -count($lines) + 1);
                $this->eraseDown();
                $this->showCursor();
                pcntl_async_signals($originalAsync);
                pcntl_signal(SIGINT, SIG_DFL);

                return $result;
            }
        } catch (\Throwable $e) {
            $this->showCursor();
            pcntl_async_signals($originalAsync);
            pcntl_signal(SIGINT, SIG_DFL);

            throw $e;
        }
    }

    /**
     * Render a static version of the spinner.
     *
     * @template TReturn of mixed
     *
     * @param  \Closure(): TReturn  $callback
     * @return TReturn
     */
    protected function renderStatically(Closure $callback): mixed
    {
        $this->static = true;

        $this->render();

        return $callback();
    }

    /**
     * Disable prompting for input.
     *
     * @throws \RuntimeException
     */
    public function prompt(): never
    {
        throw new RuntimeException('Spinner cannot be prompted.');
    }

    /**
     * Get the current value of the prompt.
     */
    public function value(): bool
    {
        return true;
    }
}
