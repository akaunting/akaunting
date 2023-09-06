<?php

namespace Spatie\Backtrace;

use RuntimeException;

class CodeSnippet
{
    /** @var int */
    protected $surroundingLine = 1;

    /** @var int */
    protected $snippetLineCount = 9;

    public function surroundingLine(int $surroundingLine): self
    {
        $this->surroundingLine = $surroundingLine;

        return $this;
    }

    public function snippetLineCount(int $snippetLineCount): self
    {
        $this->snippetLineCount = $snippetLineCount;

        return $this;
    }

    public function get(string $fileName): array
    {
        if (! file_exists($fileName)) {
            return [];
        }

        try {
            $file = new File($fileName);

            [$startLineNumber, $endLineNumber] = $this->getBounds($file->numberOfLines());

            $code = [];

            $line = $file->getLine($startLineNumber);

            $currentLineNumber = $startLineNumber;

            while ($currentLineNumber <= $endLineNumber) {
                $code[$currentLineNumber] = rtrim(substr($line, 0, 250));

                $line = $file->getNextLine();
                $currentLineNumber++;
            }

            return $code;
        } catch (RuntimeException $exception) {
            return [];
        }
    }

    public function getAsString(string $fileName): string
    {
        $snippet = $this->get($fileName);

        $snippetStrings = array_map(function (string $line, string $number) {
            return "{$number} {$line}";
        }, $snippet, array_keys($snippet));

        return implode(PHP_EOL, $snippetStrings);
    }

    protected function getBounds(int $totalNumberOfLineInFile): array
    {
        $startLine = max($this->surroundingLine - floor($this->snippetLineCount / 2), 1);

        $endLine = $startLine + ($this->snippetLineCount - 1);

        if ($endLine > $totalNumberOfLineInFile) {
            $endLine = $totalNumberOfLineInFile;
            $startLine = max($endLine - ($this->snippetLineCount - 1), 1);
        }

        return [$startLine, $endLine];
    }
}
