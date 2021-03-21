<?php

namespace Enlightn\SecurityChecker;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;

interface FormatterInterface
{
    /**
     * Display the result.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $result
     * @return void
     */
    public function displayResult(OutputInterface $output, array $result);

    /**
     * Display the error.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception $exception
     * @return void
     */
    public function displayError(OutputInterface $output, Exception $exception);
}
