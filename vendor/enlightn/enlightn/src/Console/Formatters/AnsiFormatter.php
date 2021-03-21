<?php

namespace Enlightn\Enlightn\Console\Formatters;

use Enlightn\Enlightn\Analyzers\Trace;
use Enlightn\Enlightn\Enlightn;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\TableStyle;

class AnsiFormatter implements Formatter
{
    /**
     * The category of the analyzers currently being run.
     *
     * @var string|null
     */
    protected $category = null;

    /**
     * Indicates whether to limit the number of lines or files displayed in each check.
     *
     * @var bool
     */
    protected $compactLines;

    /**
     * Called before analysis for initialization or printing a greeting message.
     *
     * @param \Illuminate\Console\Command $command
     * @return void
     */
    public function beforeAnalysis(Command $command)
    {
        $this->setColors($command->getOutput());
        $command->line(require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'logo.php');
        $command->getOutput()->newLine();
        $command->line('Please wait while Enlightn scans your code base...');

        $this->compactLines = config('enlightn.compact_lines', true);
    }

    /**
     * Called for each analyzer with the result.
     *
     * @param \Illuminate\Console\Command $command
     * @param array $result
     * @param int $current
     * @param int $total
     * @param bool $allAnalyzers
     * @return void
     */
    public function parseAnalyzerResult(Command $command, array $result, int $current, int $total, bool $allAnalyzers)
    {
        if ($this->category !== $result['category']) {
            $command->getOutput()->newLine();
            $command->line('|------------------------------------------');
            $command->line('| Running '.$result['category'].' Checks');
            $command->line('|------------------------------------------');
        }

        $command->getOutput()->newLine();
        $command->getOutput()->write("<fg=yellow>Check {$current}/{$total}: </fg=yellow>");
        $command->getOutput()->write($result['title']);
        $command->line(' '.$this->getSymbolForStatus($result['status']));

        if (! in_array($result['status'], ['passed', 'skipped'])) {
            $error = $result['error'] ?? $result['exception'];
            $command->line("<fg=red>{$error}</fg=red>");

            if ($result['status'] === 'error' && $command->option('show-exceptions')) {
                $command->line("<fg=red>{$result['stackTrace']}</fg=red>");
            }

            if (! empty($result['traces'])) {
                $this->formatTraces($command, $result['traces'], $allAnalyzers);
            }

            $command->line("<fg=cyan>Documentation URL: <href={$result['docsUrl']}>{$result['docsUrl']}</></fg=cyan>");
        }

        $this->category = $result['category'];
    }

    /**
     * Called after analysis for printing the final output.
     *
     * @param \Illuminate\Console\Command $command
     * @param bool $allAnalyzers
     * @return void
     */
    public function afterAnalysis(Command $command, bool $allAnalyzers)
    {
        if ($allAnalyzers) {
            $this->printReportCard($command);
        }
    }

    /**
     * @param \Illuminate\Console\Command $command
     * @param array $traces
     * @param bool $allAnalyzers
     */
    protected function formatTraces(Command $command, array $traces, bool $allAnalyzers)
    {
        if ($command->option('details')) {
            collect($traces)->each(function (Trace $trace) use ($command) {
                $command->line(
                    "<fg=magenta>At ".$trace->relativePath().", line ".$trace->lineNumber
                   .(is_null($trace->details) ? "." : (": ".$trace->details))."</fg=magenta>"
                );
            });

            return;
        }

        collect($traces)->groupBy(function (Trace $trace) {
            return $trace->relativePath();
        })->when($allAnalyzers && $this->compactLines, function ($collection) {
            return $collection->take(5);
        })->each(function ($traces, $path) use ($command) {
            $lineNumbers = collect($traces)->map(function (Trace $trace) {
                return $trace->lineNumber;
            })->toArray();

            $command->line(
                "<fg=magenta>At ".$path.(empty($lineNumbers) ? "" : ": line(s): ")
                .collect($lineNumbers)->join(', ', ' and ').".</fg=magenta>"
            );
        });

        $count = collect($traces)->groupBy(function (Trace $trace) {
            return $trace->relativePath();
        })->count();

        if ($count > 5 && $allAnalyzers && $this->compactLines) {
            $command->line("<fg=magenta>And "
                .($count - 5)
                ."</fg=magenta> more file(s).");
        }
    }

    /**
     * Update the result based on the analysis.
     *
     * @param \Illuminate\Console\Command $command
     * @return string
     */
    protected function printReportCard(Command $command)
    {
        $command->getOutput()->newLine();

        $command->getOutput()->title('Report Card');

        $rightAlign = (new TableStyle())->setPadType(STR_PAD_LEFT);

        $command->table(
            array_merge(['Status'], Enlightn::$categories, ['Total']),
            collect(['passed', 'failed', 'skipped', 'error'])->map(function ($status) use ($command) {
                return array_merge(
                    [$status === 'skipped' ? 'Not Applicable' : ucfirst($status)],
                    collect(array_merge(Enlightn::$categories, ['Total']))->map(function ($category) use ($status, $command) {
                        return $this->formatResult($status, $category, $command->result);
                    })->toArray()
                );
            })->values()->toArray(),
            'default',
            ['default', $rightAlign, $rightAlign, $rightAlign, $rightAlign]
        );
    }

    /**
     * Get the result with percentage for each category.
     *
     * @param string $status
     * @param string $category
     * @param array $result
     * @return string
     */
    protected function formatResult(string $status, string $category, array $result)
    {
        $totalAnalyzersInCategory = (float) collect($result[$category])->filter(function ($_, $status) {
            return in_array($status, ['passed', 'failed', 'skipped', 'error']);
        })->sum(function ($count) {
            return $count;
        });

        if ($totalAnalyzersInCategory == 0) {
            // Avoid division by zero.
            $percentage = 0;
        } else {
            $percentage = round((float) $result[$category][$status] * 100 / $totalAnalyzersInCategory, 0);
        }

        return $result[$category][$status]
            .str_pad(" ({$percentage}%)", 7, " ", STR_PAD_LEFT);
    }

    /**
     * Set the console colors for Enlightn's logo.
     *
     * @param \Illuminate\Console\OutputStyle $output
     * @return void
     */
    protected function setColors(OutputStyle $output)
    {
        collect([
            'e' => 'green',
            'n' => 'green',
            'l' => 'green',
            'i' => 'green',
            'g' => 'green',
            'h' => 'green',
            't' => 'green',
            'ns' => 'green',
        ])->each(function ($color, $tag) use ($output) {
            $output->getFormatter()->setStyle($tag, new OutputFormatterStyle($color));
        });
    }

    /**
     * Get the appropriate symbol for the status.
     *
     * @param string $status
     * @return string
     */
    protected function getSymbolForStatus(string $status)
    {
        switch ($status) {
            case 'passed':
                return '<fg=green>Passed</fg=green>';
            case 'failed':
                return '<fg=red>Failed</fg=red>';
            case 'skipped':
                return '<fg=cyan>Not Applicable</fg=cyan>';
            case 'error':
                return '<fg=magenta>Exception</fg=magenta>';
        }

        return '';
    }
}
