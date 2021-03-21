<?php

namespace Enlightn\SecurityChecker;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class AnsiFormatter implements FormatterInterface
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
        if ($count = count($result)) {
            $status = 'CRITICAL';
            $style = 'error';
        } else {
            $status = 'OK';
            $style = 'info';
        }

        $output->writeln(sprintf(
            '<%s>[%s] %d %s known vulnerabilities</>',
            $style,
            $status,
            $count,
            1 === $count ? 'package has' : 'packages have'
        ));

        if (0 !== $count) {
            $output->write("\n");

            foreach ($result as $dependency => $issues) {
                $dependencyFullName = $dependency.' ('.$issues['version'].')';
                $output->writeln('<info>'.$dependencyFullName."\n".str_repeat(
                    '-',
                    strlen($dependencyFullName)
                )."</>\n");

                foreach ($issues['advisories'] as $issue => $details) {
                    $output->write(' * ');
                    if (! empty($details['cve'])) {
                        $output->write('<comment>'.$details['cve'].': </comment>');
                    }
                    $output->writeln($details['title']);

                    if (! empty($details['link'])) {
                        $output->writeln('   '.$details['link']);
                    }

                    $output->writeln('');
                }
            }
        }
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
        $output->writeln(sprintf(
            '<error>[ERROR] Vulnerabilities scan failed with exception: %s</error>',
            $exception->getMessage()
        ));
    }
}
