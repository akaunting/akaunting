<?php

namespace Enlightn\SecurityChecker;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class JsonFormatter implements FormatterInterface
{
    /**
     * Display the result.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $result
     * @return void
     */
    public function displayResult(OutputInterface $output, array $result)
    {
        $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
    }

    /**
     * Display the error.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception $exception
     * @return void
     */
    public function displayError(OutputInterface $output, Exception $exception)
    {
        $output->writeln(json_encode([
            'error' => $exception->getMessage(),
        ], JSON_PRETTY_PRINT));
    }
}
